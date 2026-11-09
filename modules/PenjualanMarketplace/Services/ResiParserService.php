<?php

declare(strict_types=1);

namespace Modules\PenjualanMarketplace\Services;

class ResiParserService
{
    /**
     * Parse courier and tracking number from label text content.
     * Backward-compatible with previous method signature.
     *
     * @param string $pdfContent Raw text extracted from PDF or OCR
     * @return array [ 'courier' => string, 'tracking_number' => string, 'confidence' => float, 'recipient_name' => string|null ]
     */
    public function parse(string $pdfContent): array
    {
        return $this->parseFull($pdfContent);
    }

    /**
     * Comprehensive scraping of Indonesian thermal shipping labels (SPX, J&T, SiCepat, JNE, Anteraja, Ninja, IDExpress, Lion, etc.)
     * Scrapes: Courier, AWB/Resi, Marketplace Channel, Order ID, Recipient Name, Phone, Address, City,
     * Sender Name, Sender Phone, Sender City (isolated from recipient),
     * Product Table (Nama Produk, SKU, Variasi, Qty, Items), COD/Total.
     *
     * @param string $rawText
     * @return array
     */
    public function parseFull(string $rawText): array
    {
        $courier = 'UNKNOWN';
        $trackingNumber = '';
        $source = 'manual';
        $noPesanan = '';
        $recipientName = null;
        $recipientPhone = null;
        $recipientAddress = null;
        $city = null;
        $sku = null;
        $productName = null;
        $variasi = null;
        $qty = 1;
        $total = 0.0;
        $isCod = false;
        $senderName = null;
        $senderPhone = null;
        $senderCity = null;
        $confidence = 0.0;

        // Clean null bytes and normalize newlines
        $text = str_replace(["\x00", "\r\n", "\r"], ["", "\n", "\n"], $rawText);

        // Strip Shopee delivery place type badge (kotak HOME / OFFICE / KANTOR / RUMAH)
        // when appearing as standalone lines, bracketed boxes, or horizontal column noise
        $text = preg_replace('/(?<=^|\n)\s*[\[\(\{\|]?\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\]\)\}\|]?\s*(?=\n|$)/im', '', $text);
        $text = preg_replace('/(?<=^|\n)\s*[\[\(\{\|]?\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\]\)\}\|]?\s+(?=[0-9\+]{8,15}|KAB|KOTA|Pengirim)/im', '', $text);

        // Normalize common OCR tracking prefix glitches:
        // 'SPX ID : 0482910392' -> 'SPXID0482910392', 'SPX - ID 0482...' -> 'SPXID0482...'
        $text = preg_replace('/\bSPX\s*[-:]?\s*ID\s*[:\-]?\s*([0-9]{8,15}[A-Z0-9]{0,4})/i', 'SPXID$1', $text);

        // Normalize OCR phone prefix glitches ('o8', 'O8' -> '08')
        $text = preg_replace('/(?<=\s|^)[oO](8\d{8,12})\b/', '0$1', $text);

        // 1. Detect Marketplace Channel / Source
        if (preg_match('/(Shopee\s*Xpress|Shopee\s*Pay|\bShopee\b|\bSPX\b)/i', $text)) {
            $source = 'shopee';
            $confidence += 0.15;
        } elseif (preg_match('/(TikTok\s*Shop|TikTok|\bTTS\b|ByteDance|\bTT\s*Order\b|Shop\s*\|\s*tokopedia)/i', $text)) {
            $source = 'tiktok';
            $confidence += 0.15;
        } elseif (preg_match('/(Tokopedia|\bTokped\b|\bTKP\b)/i', $text)) {
            $source = 'tokopedia';
            $confidence += 0.15;
        } elseif (preg_match('/(Lazada|\bLEX\b)/i', $text)) {
            $source = 'lazada';
            $confidence += 0.15;
        }

        // 1b. Check for explicit Resi / AWB label first (highest priority)
        // Supports variants: No. Resi, No.F Resi, Resi:, AWB:, Waybill:, Rcsi, Besi, Rosi, Posi, Fesi
        // and supports bracket borders [SPXID...] or horizontal spaces/underscores in tracking candidates without bleeding across newlines
        if (preg_match('/(?:No\.?|Nomor|Nomer|AWB|No\.?\s*[A-Za-z0-9\.]*)?\s*(?:Resi|AWB|Tracking|Waybill|Airwaybill|R[ec]si|B[ec]si|Fesi|Rosi|Posi)\s*[:\|\-;\s\[\(]*([\|1IlA-Za-z0-9_\-][\|1IlA-Za-z0-9_\- \t]{6,26}[\|1IlA-Za-z0-9_\-]|[\|1IlA-Za-z0-9_\-]{8,25})/i', $text, $explicitResiMatch)) {
            $candidateTrk = trim($explicitResiMatch[1]);
            // Exclude phone number mistakenly labeled as resi
            if (!preg_match('/^(?:08|628|\+628)[0-9\-\s]{8,15}$/', $candidateTrk)) {
                $trackingNumber = $this->normalizeTrackingNumber($candidateTrk);
                if (!empty($trackingNumber)) {
                    $confidence += 0.40;
                }
            }
        }

        // 2. Detect Courier & Specific Courier Tracking Patterns
        if (preg_match('/(Shopee\s*Xpress|SPX\s*Express|SPX\s*Standard|SPX\s*Hemat|SPX\s*Ambil|SPX\s*Sameday|SPX\s*Instant|\bSPX(?:ID)?\b|\bSPX[A-Z0-9_\-]+)/i', $text)) {
            $courier = preg_match('/(?:INSTANT|SPX\s*Instant)/i', $text) ? 'SPX Instant' : 'SPX Express';
            $confidence += 0.35;
            // SPX tracking numbers: SPXID... or ID25838628439GK (or OCR variants |D / 1D / lD)
            if (empty($trackingNumber)) {
                if (preg_match('/(?:\bSPX[-_\s]?(?:[\|1Il]?D)[0-9O]{8,15}[A-Z0-9]{0,4}\b|\bSPX[-_\s]?[0-9O]{9,15}[A-Z0-9]{0,4}\b)/i', $text, $matches)) {
                    $cand = $this->normalizeTrackingNumber($matches[0], 'SPX Express');
                    if (!empty($cand)) {
                        $trackingNumber = $cand;
                        $confidence += 0.45;
                    }
                } elseif (preg_match('/(?<=^|[\s:;\|\-])([\|1Il]?D\s*[0-9O]{9,15}[A-Z0-9]{0,4})(?=[^A-Za-z0-9]|$)/i', $text, $matches)) {
                    $cand = $this->normalizeTrackingNumber($matches[1], 'SPX Express');
                    if (!empty($cand)) {
                        $trackingNumber = $cand;
                        $confidence += 0.45;
                    }
                }
            }
        } elseif (preg_match('/([JS]\s*&\s*T(?:\s*Express)?|JET(?:\s*Express)?|J&T\s*Cargo|JNT\s*Express|JNT|\bJ&T\b)/i', $text)) {
            $courier = 'J&T Express';
            $confidence += 0.35;
            if (empty($trackingNumber)) {
                if (preg_match('/\b(JP[0-9]{10,12}|JT[0-9]{10,12}|JZ[0-9]{10,12}|JX[0-9]{10,12}|JNT[0-9]{10,12}|(?!(?:08|62))[0-9]{12})\b/i', $text, $matches)) {
                    $trackingNumber = strtoupper($matches[1]);
                    $confidence += 0.45;
                }
            }
        } elseif (preg_match('/(SiCepat\s*Express|SiCepat|HALU|SIUNTUNG|GOKIL)/i', $text)) {
            $courier = 'SiCepat';
            $confidence += 0.35;
            if (empty($trackingNumber)) {
                if (preg_match('/\b(00[0-9]{10,12}|01[0-9]{10,12}|02[0-9]{10,12}|(?!(?:08|62))[0-9]{12})\b/', $text, $matches)) {
                    $trackingNumber = $matches[1];
                    $confidence += 0.45;
                }
            }
        } elseif (preg_match('/(JNE\s*Express|JNE\s*REG|JNE\s*OKE|JNE\s*YES|\bJNE\b)/i', $text)) {
            $courier = 'JNE';
            $confidence += 0.35;
            if (empty($trackingNumber)) {
                if (preg_match('/\b((?:JNE|CM|TL|SOC|CGK|BDO)[0-9]{10,15}|[0-9]{15,16})\b/i', $text, $matches)) {
                    $trackingNumber = strtoupper($matches[1]);
                    $confidence += 0.45;
                }
            }
        } elseif (preg_match('/(Anteraja|AnterAja)/i', $text)) {
            $courier = 'Anteraja';
            $confidence += 0.35;
            if (empty($trackingNumber)) {
                if (preg_match('/\b(1000[0-9]{10}|100[0-9]{11}|[0-9]{14})\b/', $text, $matches)) {
                    $trackingNumber = $matches[1];
                    $confidence += 0.45;
                }
            }
        } elseif (preg_match('/(Ninja\s*Xpress|Ninja\s*Van|\bNinja\b)/i', $text)) {
            $courier = 'Ninja Xpress';
            $confidence += 0.35;
            if (empty($trackingNumber)) {
                if (preg_match('/\b(NLID[A-Z0-9]{10,15}|NINJA[A-Z0-9]{8,14}|SHP[A-Z0-9]{9,15})\b/i', $text, $matches)) {
                    $trackingNumber = strtoupper($matches[1]);
                    $confidence += 0.45;
                }
            }
        } elseif (preg_match('/(ID\s*Express|IDE|\bIDS\b|iDX)/i', $text)) {
            $courier = 'ID Express';
            $confidence += 0.35;
            if (empty($trackingNumber)) {
                if (preg_match('/\b(IDE[0-9]{10,14}|IDS[0-9]{10,14}|IDP[0-9]{10,14})\b/i', $text, $matches)) {
                    $trackingNumber = strtoupper($matches[1]);
                    $confidence += 0.45;
                }
            }
        } elseif (preg_match('/(Lion\s*Parcel|\bLion\b)/i', $text)) {
            $courier = 'Lion Parcel';
            $confidence += 0.35;
            if (empty($trackingNumber)) {
                if (preg_match('/\b(LP[0-9]{10,14}|(?!(?:08|62))[0-9]{12})\b/i', $text, $matches)) {
                    $trackingNumber = strtoupper($matches[1]);
                    $confidence += 0.45;
                }
            }
        }

        // Generic fallback for Tracking Number (AWB / No. Resi) if not yet resolved
        if (empty($trackingNumber)) {
            if (preg_match('/(?:No\.?|Nomor|Nomer|AWB|No\.?\s*[A-Za-z0-9\.]*)?\s*(?:Resi|AWB|Tracking|Waybill|Airwaybill|R[ec]si|B[ec]si|Fesi|Rosi|Posi)\s*[:\|\-;\s\[\(]*([\|1IlA-Za-z0-9_\-][\|1IlA-Za-z0-9_\- \t]{6,26}[\|1IlA-Za-z0-9_\-]|[\|1IlA-Za-z0-9_\-]{8,25})/i', $text, $m)) {
                $cand = $this->normalizeTrackingNumber($m[1], $courier);
                if (!empty($cand)) {
                    $trackingNumber = $cand;
                    $confidence += 0.40;
                }
            } elseif (preg_match('/(?:\bSPX[-_\s]?(?:[\|1Il]?D)[0-9O]{8,15}[A-Z0-9]{0,4}\b|\bSPX[-_\s]?[0-9O]{9,15}[A-Z0-9]{0,4}\b)/i', $text, $m)) {
                $cand = $this->normalizeTrackingNumber($m[0], 'SPX Express');
                if (!empty($cand)) {
                    $trackingNumber = $cand;
                    $confidence += 0.40;
                    if ($courier === 'UNKNOWN') {
                        $courier = 'SPX Express';
                    }
                }
            } elseif (preg_match('/(?<=^|[\s:;\|\-])([\|1Il]?D\s*[0-9O]{9,15}[A-Z0-9]{0,4})(?=[^A-Za-z0-9]|$)/i', $text, $m)) {
                $cand = $this->normalizeTrackingNumber($m[1], 'SPX Express');
                if (!empty($cand)) {
                    $trackingNumber = $cand;
                    $confidence += 0.40;
                    if ($courier === 'UNKNOWN') {
                        $courier = 'SPX Express';
                    }
                }
            } elseif (preg_match('/\b(SPXID[0-9]{8,14}|JP[0-9]{10,12}|JT[0-9]{10,12}|TKP[A-Z0-9]{8,15})\b/i', $text, $m)) {
                $trackingNumber = strtoupper($m[1]);
                $confidence += 0.35;
            }
        }

        // Infer courier from tracking prefix if still unknown
        if ($courier === 'UNKNOWN' && !empty($trackingNumber)) {
            if (str_starts_with($trackingNumber, 'SPX') || preg_match('/^ID\d{9,14}/', $trackingNumber)) {
                $courier = 'SPX Express';
                if ($source === 'manual') $source = 'shopee';
            } elseif (str_starts_with($trackingNumber, 'JP') || str_starts_with($trackingNumber, 'JT') || str_starts_with($trackingNumber, 'JZ') || str_starts_with($trackingNumber, 'JX')) {
                $courier = 'J&T Express';
            } elseif (str_starts_with($trackingNumber, 'TKP')) {
                $courier = 'Tokopedia Logistic';
                if ($source === 'manual') $source = 'tokopedia';
            } elseif (str_starts_with($trackingNumber, 'IDE') || str_starts_with($trackingNumber, 'IDS')) {
                $courier = 'ID Express';
            } elseif (str_starts_with($trackingNumber, 'NLID') || str_starts_with($trackingNumber, 'NINJA')) {
                $courier = 'Ninja Xpress';
            } elseif (str_starts_with($trackingNumber, 'JNE') || preg_match('/^(?:CM|TL|BDO|SOCG?)\d/i', $trackingNumber)) {
                $courier = 'JNE';
            } elseif (str_starts_with($trackingNumber, '00') || str_starts_with($trackingNumber, 'SOC')) {
                $courier = 'SiCepat';
            } elseif (str_starts_with($trackingNumber, 'POS') || str_starts_with($trackingNumber, '169') || str_starts_with($trackingNumber, '179')) {
                $courier = 'Pos Indonesia';
            } elseif (str_starts_with($trackingNumber, 'LP')) {
                $courier = 'Lion Parcel';
            }
        }

        // 3. Detect Order ID / No. Pesanan (including Shopee pesan: (2403264080DMO5) & TT Order Id)
        if (preg_match('/(?:TT\s*Order\s*(?:Id|ID)|No\.?\s*Pesanan|Order\s*ID|No\.?\s*Order|No\.?\s*Transaksi|Invoice)\s*[:\-]?\s*([A-Za-z0-9_\-\/]{8,35})/i', $text, $m)) {
            $noPesanan = trim($m[1]);
            if (stripos($m[0], 'TT') !== false && ($source === 'manual' || $source === 'tokopedia')) {
                $source = 'tiktok';
            }
        } elseif (preg_match('/(?:No\.?\s*Pesanan|Order\s*ID|No\.?\s*Order)\s*[:\-]?\s*(?:[A-Za-z]{1,4}\s+)?[\r\n]*([A-Za-z0-9_\-\/]{8,35})/i', $text, $m)) {
            $noPesanan = trim($m[1]);
        } elseif (preg_match('/(?:pesan|message)\s*[:\-]?\s*\(?([A-Za-z0-9]{10,25})\)?/i', $text, $m)) {
            $noPesanan = trim($m[1]);
            if ($source === 'manual') $source = 'shopee';
        } elseif (preg_match('/\b(2[3-9][0-1][0-9][0-3][0-9][A-Z0-9]{5,18}(?:SHP)?)\b/i', $text, $m)) {
            $noPesanan = trim($m[1]);
            if ($source === 'manual') $source = 'shopee';
        } elseif (preg_match('/(INV\/\d{4,8}\/\w+\/\d+)/i', $text, $m)) {
            $noPesanan = trim($m[1]);
            if ($source === 'manual') $source = 'tokopedia';
        } elseif (preg_match('/\b(57\d{14,17})\b/', $text, $m)) {
            $noPesanan = trim($m[1]);
            if ($source === 'manual' || $source === 'tokopedia') $source = 'tiktok';
        }

        // 4. Extract Sender Block (Pengirim / Toko) - isolate it so it never contaminates Recipient
        $lines = explode("\n", $text);
        $pengirimLineIdx = -1;

        foreach ($lines as $idx => $line) {
            if (preg_match('/(?:^|\s)(?:Pengirim|Sender|Dari|Toko|Seller)\s*[:\-]\s*(.*)/i', $line, $pm)) {
                $pengirimLineIdx = $idx;
                $senderRaw = trim($pm[1]);
                $cleanName = preg_replace('/(?:\s*(?:\||\b(?:Telp|HP|Phone|WA)\b|\(?\+?62\)?|\(?08\)?|KOTA|KAB).*)/i', '', $senderRaw);
                $cleanName = trim(trim($cleanName), "()[]-:, \t");
                $senderName = $cleanName ?: null;

                if (preg_match('/(?:\(\+?62\)\s*|\+?62|\(?0)(8[0-9\-\s]{8,15})/', $senderRaw, $phm)) {
                    $senderPhone = $this->normalizePhone($phm[0]);
                }
                if (preg_match('/(?:KOTA|KAB\.?|KABUPATEN)\s+([A-Za-z\s]{3,25})/i', $senderRaw, $cm)) {
                    $senderCity = ucwords(strtolower(trim($cm[1])));
                }
                break;
            }
        }

        // If phone or city not yet found on Pengirim line, scan next 1-4 lines (e.g. Image 3 vertical layout)
        if ($pengirimLineIdx !== -1) {
            for ($i = $pengirimLineIdx; $i <= min(count($lines) - 1, $pengirimLineIdx + 4); $i++) {
                $l = trim($lines[$i]);
                if ($i > $pengirimLineIdx && preg_match('/^(?:Penerima|Kepada|No\.?\s*Pesanan|Order\s*ID|COD|Nama\s*Produk|Total|CASHLESS)/i', $l)) {
                    break;
                }

                if (empty($senderPhone)) {
                    if (preg_match_all('/(?:\(\+?62\)\s*8\d{8,12}|\b628\d{8,12}\b|\b08\d{8,12}\b|\+628[0-9\-\s]{8,15})/', $l, $allPh)) {
                        $lastPh = end($allPh[0]);
                        $senderPhone = $this->normalizePhone($lastPh);
                    }
                }

                if (empty($senderCity)) {
                    $citiesOnLine = $this->extractAllCities($l);
                    if (!empty($citiesOnLine)) {
                        $senderCity = end($citiesOnLine);
                    } elseif (preg_match('/(?:DKI\s+JAKARTA|\bJAKARTA\b)/i', $l)) {
                        $senderCity = 'Jakarta';
                    } elseif (preg_match('/(?:JAWA\s*BARAT|JAWA\s*TENGAH|JAWA\s*TIMUR|BANTEN|DKI),?\s*([A-Za-z\s]{3,20})/i', $l, $provCityM)) {
                        $cCand = $this->detectCity($provCityM[1]);
                        if ($cCand) {
                            $senderCity = $cCand;
                        }
                    }
                }
            }
        }

        // 5. Extract Recipient Block (separating from horizontal Sender column)
        $penerimaIndex = -1;
        $recipientLines = [];

        foreach ($lines as $idx => $line) {
            if ($penerimaIndex === -1 && preg_match('/(?:^|\s)(?:Penerima|Kepada|Deliver\s*To|\bTo\b|Buyer|Nama\s*Penerima)\s*[:\-]?/i', $line)) {
                $penerimaIndex = $idx;
            }

            if ($penerimaIndex !== -1 && $idx >= $penerimaIndex) {
                $trimmed = trim($line);
                // Stop at section boundaries
                if ($idx > $penerimaIndex && preg_match('/^(?:Berat|Weight|No\.?\s*Pesanan|Order\s*ID|CASHLESS|COD|Non-COD|Catatan|SKU|Item|Qty|Pratinjau|Barcode|Total|Nama\s*Produk)/i', $trimmed)) {
                    break;
                }

                // Strip horizontal sender column and sender data ONLY when sender is on the same line (side-by-side)
                $cleanCol = preg_replace('/\s+(?:Pengirim|Sender|Dari|Toko|Seller)\s*:.*/i', '', $line);
                if ($pengirimLineIdx === $penerimaIndex || ($pengirimLineIdx !== -1 && abs($pengirimLineIdx - $penerimaIndex) <= 1 && preg_match('/(?:Pengirim|Sender|Dari|Toko|Seller)/i', $line))) {
                    if (!empty($senderPhone)) {
                        $rawDigits = preg_replace('/^0/', '', $senderPhone);
                        $cleanCol = preg_replace('/\s*(?:62|0)?' . preg_quote($rawDigits, '/') . '\b.*/i', '', $cleanCol);
                    }
                    if (!empty($senderCity)) {
                        $cleanCol = preg_replace('/(?:\s*(?:KOTA|KAB\.?|KABUPATEN)?\s*' . preg_quote($senderCity, '/') . '\b.*)/i', '', $cleanCol);
                    }
                }
                $cleanCol = preg_replace('/(?:\bKAB\.\s*BENGKULU[^\n]*)/i', '', $cleanCol);

                // Strip Shopee delivery place type badge (kotak HOME / OFFICE / KANTOR / RUMAH)
                $cleanCol = preg_replace('/^\s*[\(\[\{\|]??\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\)\]\}\|]??(?:\s+|$)/i', '', $cleanCol);
                $cleanCol = preg_replace('/\s*[\(\[\{\|]??\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\)\]\}\|]??\s*$/i', '', $cleanCol);

                $cleanCol = trim($cleanCol);
                if (!empty($cleanCol)) {
                    $recipientLines[] = $cleanCol;
                }
            }
        }

        // 5b. Detect Recipient Name (Nama Penerima)
        if (preg_match('/(?:^|\n|\r|\s)(?:Penerima|Kepada|Deliver\s*To|\bTo\b|Buyer|Nama\s*Penerima)\s*[:\-]?\s*([^\r\n]{2,80})/i', $text, $nameMatches)) {
            $candidateName = trim($nameMatches[1]);
            $candidateName = preg_replace('/\s+(?:Pengirim|Sender|Dari|Toko|Seller|Telp|HP|Phone|\+?62|08|Alamat|Address).*/i', '', $candidateName);
            $candidateName = preg_replace('/\b(?:HOME|OFFICE|KANTOR|RUMAH)\b/i', '', $candidateName);
            $candidateName = preg_replace('/\s*\([^)]*\)/', '', $candidateName);
            $candidateName = preg_replace('/[0-9\+\-\(\)]{6,}/', '', $candidateName);
            $candidateName = trim(trim($candidateName), "()[]-:, \t");
            $recipientName = $candidateName;
        }

        // If recipientName is still empty or is just a delivery type badge like HOME, and Penerima: was on its own line, check subsequent lines
        if ((empty($recipientName) || preg_match('/^(?:HOME|OFFICE|KANTOR|RUMAH)$/i', $recipientName)) && $penerimaIndex !== -1) {
            $recipientName = '';
            for ($k = $penerimaIndex + 1; $k <= min(count($lines) - 1, $penerimaIndex + 4); $k++) {
                $nextLine = trim($lines[$k]);
                if (empty($nextLine) || preg_match('/^[\(\[\{\|]??\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\)\]\}\|]??\s*$/i', $nextLine)) {
                    continue;
                }
                if (!preg_match('/^(?:Alamat|Address|Telp|Phone|HP|WA|No\.?|Kota|Kab|Kec|Pengirim|Sender)/i', $nextLine)) {
                    $nextLineClean = preg_replace('/\s+(?:Pengirim|Sender|Dari|Toko|Seller|Telp|HP|Phone|\+?62|08).*/i', '', $nextLine);
                    $nextLineClean = preg_replace('/^\s*[\(\[\{\|]??\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\)\]\}\|]??(?:\s+|$)/i', '', $nextLineClean);
                    $nextLineClean = preg_replace('/\b(?:HOME|OFFICE|KANTOR|RUMAH)\b/i', '', $nextLineClean);
                    $nextLineClean = preg_replace('/[0-9\+\-\(\)]{6,}/', '', $nextLineClean);
                    $candTrim = trim(trim($nextLineClean), "()[]-:, \t");
                    if (!empty($candTrim) && !preg_match('/^(?:HOME|OFFICE|KANTOR|RUMAH)$/i', $candTrim)) {
                        $recipientName = $candTrim;
                        break;
                    }
                }
            }
        }

        // 5c. Detect Recipient Phone (strictly excluding sender phone)
        $recipientText = implode("\n", $recipientLines);

        // Check if phone was on the same line as Penerima:
        if (preg_match('/(?:^|\n|\r|\s)(?:Penerima|Kepada|Deliver\s*To|\bTo\b|Buyer|Nama\s*Penerima)\s*[:\-]?\s*.*?(?:\(\+?62\)\s*|\+?62|\(?0)(8[0-9\-\s]{8,15})/i', $text, $recPhMatches)) {
            $cand = $this->normalizePhone($recPhMatches[1]);
            if (empty($senderPhone) || $cand !== $senderPhone) {
                $recipientPhone = $cand;
            }
        }

        if (empty($recipientPhone)) {
            if (preg_match('/(?:Telp|Telepon|HP|Phone|WA|No\.?\s*(?:Telp|HP|Telepon))\s*[:\-]?\s*(\(?\+?62\)?\s*[0-9\-\s]{8,16}|08[0-9\-\s]{8,15})/i', $recipientText, $phoneMatches)) {
                $recipientPhone = $this->normalizePhone($phoneMatches[1]);
            } elseif (preg_match('/(?:Telp|Telepon|HP|Phone|WA|No\.?\s*(?:Telp|HP|Telepon))\s*[:\-]?\s*(\(?\+?62\)?\s*[0-9\-\s]{8,16}|08[0-9\-\s]{8,15})/i', $text, $phoneMatches)) {
                $cand = $this->normalizePhone($phoneMatches[1]);
                if (empty($senderPhone) || $cand !== $senderPhone) {
                    $recipientPhone = $cand;
                }
            }
        }

        if (empty($recipientPhone)) {
            if (preg_match_all('/(?:\(\+?62\)\s*8[0-9]{8,12}|\b\+?628[0-9]{8,12}\b|\b08[0-9]{8,12}\b)/', $text, $allPhones)) {
                foreach ($allPhones[0] as $pCandidate) {
                    $normP = $this->normalizePhone($pCandidate);
                    if (!empty($senderPhone) && $normP === $senderPhone) {
                        continue;
                    }
                    $recipientPhone = $normP;
                    break;
                }
            }
        }

        // If recipient has no phone number on label, keep it empty per user requirement
        // (do NOT fallback to sender phone!)

        // 5d. Detect Recipient City & Address
        $city = $this->extractCityFromText($recipientText);
        if (empty($city)) {
            if (preg_match('/(?:KAB\.?|KABUPATEN|KOTA)\s+([A-Za-z\s]{3,25})\s*\|/i', $text, $cm)) {
                $city = ucwords(strtolower(trim($cm[1])));
            }
        }
        if (empty($city)) {
            $candidateCity = $this->detectCity($recipientText);
            if ($candidateCity) {
                $city = $candidateCity;
            } elseif (empty($senderCity) || stripos($text, $senderCity) !== false) {
                $detectedInFull = $this->detectCity($text);
                if ($detectedInFull && (empty($senderCity) || strcasecmp($detectedInFull, $senderCity) !== 0)) {
                    $city = $detectedInFull;
                }
            }
        }

        // Avoid misidentifying sender city as recipient city if recipient is in a different city
        if (empty($city) || (!empty($senderCity) && strcasecmp($city, $senderCity) === 0)) {
            if (preg_match('/(?:KABUPATEN|KAB\.?|KOTA)\s*(?:[0-9\+\(\)\s]{5,18})?[\r\n]+\s*([A-Za-z\s]{3,20})/i', $recipientText ?: $text, $splitCityM)) {
                $c = $this->detectCity(trim($splitCityM[1]));
                if ($c && (empty($senderCity) || strcasecmp($c, $senderCity) !== 0)) {
                    $city = $c;
                }
            }
        }

        // Address parts from recipientLines
        $addressParts = [];
        foreach ($recipientLines as $i => $l) {
            if ($i === 0 && preg_match('/(?:Penerima|Kepada|To)\s*:/i', $l)) {
                continue;
            }
            // Skip line matching recipient name when name was on a separate line
            if (!empty($recipientName) && strcasecmp(trim($l), trim($recipientName)) === 0) {
                continue;
            }
            // Skip standalone delivery place type badges (HOME / OFFICE / KANTOR / RUMAH)
            if (preg_match('/^[\(\[\{\|]??\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\)\]\}\|]??\s*$/i', $l)) {
                continue;
            }
            if (preg_match('/\|/', $l) && strlen($l) < 30) {
                continue;
            }
            if (!empty($recipientPhone) && str_contains($this->normalizePhone($l), $recipientPhone)) {
                continue;
            }
            // Strip delivery place badge prefix from address line
            $cleanPart = preg_replace('/^\s*[\(\[\{\|]??\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\)\]\}\|]??(?:\s*,\s*|\s+)/i', '', $l);
            $cleanPart = trim($cleanPart);
            if (!empty($cleanPart) && !preg_match('/^[\(\[\{\|]??\s*(?:HOME|OFFICE|KANTOR|RUMAH)\s*[\)\]\}\|]??\s*$/i', $cleanPart)) {
                $addressParts[] = $cleanPart;
            }
        }
        if (!empty($addressParts)) {
            $recipientAddress = implode(', ', $addressParts);
            $recipientAddress = preg_replace('/\s*,\s*,+/', ',', $recipientAddress);
            $recipientAddress = preg_replace('/^(?:HOME|OFFICE|KANTOR|RUMAH)\s*,\s*/i', '', $recipientAddress);
            $recipientAddress = preg_replace('/\b(?:HOME|OFFICE|KANTOR|RUMAH)\b\s*,\s*/i', '', $recipientAddress);
            $recipientAddress = trim(preg_replace('/\s+/', ' ', $recipientAddress));
        }

        if ($city && empty($recipientAddress)) {
            $recipientAddress = 'Kota ' . $city;
        }

        // 6. Detect Product Table & Multi-Item Data (Nama Produk, SKU, Variasi, Qty)
        $parsedItems = $this->parseProductTable($text, $lines);
        if (!empty($parsedItems)) {
            $first = $parsedItems[0];
            $productName = $first['nama_produk'] ?? '';
            $sku = $first['sku'] ?? '';
            $variasi = $first['variasi'] ?? '';
            $qty = 0;
            foreach ($parsedItems as $it) {
                $qty += max(1, (int) ($it['qty'] ?? 1));
            }
            // "Barang : hijau" on J&T TikTok resi = variasi (warna), not SKU — only use as SKU fallback if no variasi found
            if (empty($sku) && empty($variasi) && preg_match('/(?:Barang)\s*[:\-]\s*([A-Za-z0-9_\-\.]{1,25})/i', $text, $bm)) {
                $sku = trim($bm[1]);
                $parsedItems[0]['sku'] = $sku;
            } elseif (empty($sku) && empty($variasi) === false && preg_match('/(?:Barang)\s*[:\-]\s*([A-Za-z0-9_\-\.]{1,25})/i', $text, $bm)) {
                // variasi already set from table; "Barang : X" is a duplicate warna — ignore for SKU
            }
        } else {
            // Fallback for standalone SKU / Variasi / Qty patterns even without explicit table header

            // TikTok Shop J&T: "Jumlah : Npcs, Barang : warna" pattern — extract variasi from "Barang"
            if (empty($variasi) && preg_match('/Jumlah\s*[:\-]\s*(\d+)\s*pcs?\s*,?\s*Barang\s*[:\-]\s*([A-Za-z0-9_\-\.\s]{1,30})/i', $text, $jtm)) {
                $qty = max(1, (int) $jtm[1]);
                $variasi = trim($jtm[2]);
            }
            if (preg_match('/(?:^|\n)\s*(?:#?\s*\d+[\.\)]?\s+)?(?:(.*?)\s+)?((?:Link\s+CO\s+[0-9\-]+|PRD-[A-Z0-9\-]+|[A-Z0-9_\-]{3,20}))\s+([A-Za-z0-9_\-\.\/]{1,15})\s+(\d{1,4})(?=\s*[\r\n]|$)/i', $text, $m)) {
                $productName = trim($m[1] ?? '');
                $sku = $this->normalizeSku($m[2]);
                $variasi = trim($m[3]);
                $qty = max(1, (int)$m[4]);
                $parsedItems[] = [
                    'nama_produk' => $productName ?: '',
                    'sku'         => $sku,
                    'variasi'     => $variasi,
                    'qty'         => $qty,
                ];
            } else {
                // Fallback for single labeled SKU/Qty format
                if (preg_match('/\b(PR[DO0]-[A-Z0-9]{3,8})\b/i', $text, $skuMatches)) {
                    $sku = $this->normalizeSku($skuMatches[1]);
                } elseif (preg_match('/(?:SKU|Kode\s*Barang|Barang)\s*[:\-]\s*([A-Za-z0-9_\-\s]{1,25})/i', $text, $skuMatches)) {
                    $sku = $this->normalizeSku($skuMatches[1]);
                }

                if (preg_match('/(?:Nama\s*Produk|Produk)\s*[:\-]\s*([^\r\n]{3,80})/i', $text, $pMatches)) {
                    $productName = trim($pMatches[1]);
                }

                if (preg_match('/(?:Variasi|Variant|Warna|Size)\s*[:\-]\s*([^\r\n]{1,30})/i', $text, $vMatches)) {
                    $variasi = trim($vMatches[1]);
                }

                if (preg_match('/(?:Qty|Kuantitas|Jumlah|x)\s*[:\-]?\s*(\d{1,4})/i', $text, $qtyMatches)) {
                    $qty = max(1, (int) $qtyMatches[1]);
                }

                if (!empty($sku) || !empty($productName)) {
                    $parsedItems[] = [
                        'nama_produk' => $productName ?: '',
                        'sku'         => $sku ?: '',
                        'variasi'     => $variasi ?: '',
                        'qty'         => $qty,
                    ];
                }
            }
        }

        // 7. Detect Total Belanja / COD Amount
        $isNonCodFlag = (bool) preg_match('/(?:\bCASHLESS\b|\bNON[\-\s]?COD\b|COD\s*[:\-]?\s*(?:Rp\.?\s*)?0+(?:[\.,]00)?(?!\d))/i', $text);

        // Priority 1: Explicit COD (e.g. COD: Rp41040, COD: 129.376, COD : 129 376, C0D, C.O.D, C O D, COO, Con, COD (Bayar di Tempat): Rp 427816)
        if (!$isNonCodFlag && preg_match('/(?<![A-Za-z0-9_\-])(?:C\s*\.?\s*[O0on]\s*\.?\s*[D0oO]\.?|COD|C0D|COO|Con|CoD|Cash\s*On\s*Delivery|\bBayar\s*di\s*Tempat\b)\s*(?:\([^)]*\)|\[[^\]]*\]|[A-Za-z_\-\/]{1,20})?[:\|\-\.;\s]*\s*(?:Rp\.?|IDR)?\s*([0-9Il]{1,4}(?:\s*[\.,]\s*[0-9IlO]{2,4}|\s+[0-9IlO]{3})+|[0-9IlO\.,]{3,15})/i', $text, $totalMatches)) {
            $parsedAmount = $this->parseCurrency($totalMatches[1]);
            if ($parsedAmount > 0) {
                $total = $parsedAmount;
                $isCod = true;
            }
        } elseif (!$isNonCodFlag && preg_match('/(?:Total\s*(?:Tagihan|Pembayaran|Belanja|Bayar|COD|Pesanan|Harga)|Nilai\s*COD|Tagihan\s*COD|Bayar\s*di\s*Tempat)\s*(?:\([^)]*\)|\[[^\]]*\]|[A-Za-z_\-\/]{1,20})?[:\|\-\.;\s]*\s*(?:Rp\.?|IDR)?\s*([0-9Il]{1,4}(?:\s*[\.,]\s*[0-9IlO]{2,4}|\s+[0-9IlO]{3})+|[0-9IlO\.,]{3,15})/i', $text, $totalMatches)) {
            $parsedAmount = $this->parseCurrency($totalMatches[1]);
            if ($parsedAmount > 0) {
                $total = $parsedAmount;
                if (stripos($totalMatches[0], 'COD') !== false || stripos($totalMatches[0], 'Bayar di Tempat') !== false) {
                    $isCod = true;
                }
            }
        } elseif (preg_match('/(?<!Diskon[\s:])(?<!Potongan[\s:])(?<!Biaya[\s:])(?<!Ongkir[\s:])(?<!Ongkos[\s:])(?<!TOTAL\sBiaya[\s:])(?:Rp\.?|IDR)\s*([0-9]{1,4}(?:\s*[\.,]\s*[0-9]{2,4}|\s+[0-9]{3})+|[0-9\.,]{3,15})/i', $text, $totalMatches)) {
            $total = $this->parseCurrency($totalMatches[1]);
        }

        // Secondary COD verification: If label contains standalone COD keyword / watermark and no Cashless/Non-COD flag
        if (!$isNonCodFlag && !$isCod && preg_match('/(?:\bC[O0o]D\b|C\s*\.?\s*[O0o]\s*\.?\s*D|\bBayar\s*di\s*Tempat\b|\bCash\s*On\s*Delivery\b)/i', $text)) {
            $isCod = true;
        }

        if ($isNonCodFlag) {
            $isCod = false;
        }

        // When order is COD and has a single item with qty 1, assign total as item price
        if ($isCod && $total > 0 && !empty($parsedItems) && count($parsedItems) === 1) {
            $itemQty = max(1, (int)($parsedItems[0]['qty'] ?? 1));
            $parsedItems[0]['harga_satuan'] = round($total / $itemQty);
        }

        // 8. Detect Shipping Deadline / Order Date ("Batas Kirim", "Kirim Sebelum", "Ship :", "Tanggal Pesanan")
        $shipDate = null;
        if (preg_match('/(?:Ship|Tgl|Tanggal)\s*[:\-]\s*([0-9]{1,4}\s*[\-\/\.]\s*[0-9]{1,2}\s*[\-\/\.]\s*[0-9]{2,4}|[0-9]{1,2}\s*[\-\s\/]\s*[A-Za-z]{3,10}\s*[\-\s\/]\s*[0-9]{2,4})/i', $text, $sdm)) {
            $shipDate = $this->normalizeDate($sdm[1]);
        }
        $batasKirim = $this->extractBatasKirim($text);
        $orderDate = $shipDate ?: ($batasKirim ?: date('Y-m-d'));
        if (!empty($batasKirim) || !empty($shipDate)) {
            $confidence += 0.1;
        }

        // For Instant / Sameday / Non-AWB labels, use Order ID as tracking number fallback
        if (empty($trackingNumber) && !empty($noPesanan)) {
            $trackingNumber = $noPesanan;
            $confidence += 0.35;
        } elseif (empty($noPesanan) && !empty($trackingNumber)) {
            $noPesanan = 'RESI-' . $trackingNumber;
        }

        if (!empty($recipientName)) {
            $confidence += 0.1;
        }
        if (!empty($recipientPhone)) {
            $confidence += 0.1;
        }
        if (!empty($recipientAddress) && $recipientAddress !== 'Indonesia') {
            $confidence += 0.15;
        }

        return [
            'courier'           => $courier,
            'tracking_number'   => $trackingNumber,
            'source'            => $source,
            'no_pesanan'        => $noPesanan,
            'recipient_name'    => $recipientName ?: '',
            'recipient_phone'   => $recipientPhone ?: '',
            'recipient_address' => $recipientAddress ?: ($city ? 'Kota ' . $city : ''),
            'city'              => $city ?: '',
            'sender_name'       => $senderName ?: '',
            'sender_phone'      => $senderPhone ?: '',
            'sender_city'       => $senderCity ?: '',
            'is_cod'            => $isCod,
            'total'             => $total,
            'sku'               => $sku ?: '',
            'product_name'      => $productName ?: '',
            'variasi'           => $variasi ?: '',
            'qty'               => $qty,
            'items'             => $parsedItems,
            'tanggal'           => $orderDate,
            'batas_kirim'       => $batasKirim,
            'confidence'        => round(min(1.0, $confidence), 2),
            'raw_text'          => $text,
        ];
    }

    /**
     * Parse multi-column Product Table from label text (e.g. Nama Produk | SKU | Variasi | Qty).
     * Handles single-line items, pipe delimiters, and complex multi-line text wrapping across columns.
     */
    public function parseProductTable(string $text, array $lines): array
    {
        $items = [];
        $headerPattern = '/(?:(?:Nama\s*Produk|Nama\s*Barang|Product\s*Name|Item|Deskripsi).*?(?:SKU|Kode|Variasi|Variant|Qty|Jumlah|Seller\s*SKU)|\bSKU\b.*?(?:Variasi|Variant|Warna|Size|Qty|Jumlah|Kuantitas|Seller\s*SKU)|\bVariasi\b.*?(?:SKU|Qty|Jumlah))/i';
        $inTable = false;
        $tableLines = [];
        $headerLine = '';

        // 1. Collect lines belonging to table
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (!$inTable) {
                if (preg_match($headerPattern, $trimmed)) {
                    $inTable = true;
                    $headerLine = $trimmed;
                    continue;
                }
            } else {
                if (empty($trimmed)) continue;
                // Section boundaries stopping table
                if (preg_match('/^(?:pesan\s*:|total(?!\s*qty)|cod|catatan|note|berat|weight|---|\={3,}|\-{3,}|CASHLESS|kurir|ekspedisi|Qty\s*Total|Total\s*Qty|Shop\s*\||TikTok\s*Shop|Order\s*ID|Package\s*ID|TT\s*Order)/i', $trimmed)) {
                    break;
                }
                $tableLines[] = $trimmed;
            }
        }

        if (empty($tableLines)) {
            return [];
        }

        // Detect TikTok Shop header: "Product Name   SKU[Seller SKU]   Qty"
        // In TikTok format, col[1] = variasi/warna (not internal SKU), col[2] = qty
        $isTikTokHeader = !empty($headerLine) && preg_match('/Product\s*Name.*SKU\s*\[Seller\s*SKU\]/i', $headerLine);

        // 2. Check if table uses pipe '|' delimiter (require at least 2 pipes on a line)
        $pipeLinesCount = 0;
        foreach ($tableLines as $tl) {
            if (substr_count($tl, '|') >= 2) {
                $pipeLinesCount++;
            }
        }
        $hasPipes = ($pipeLinesCount >= 1 && count($tableLines) <= 2) || ($pipeLinesCount >= 2);

        if ($hasPipes) {
            foreach ($tableLines as $line) {
                if (!str_contains($line, '|')) continue;
                $cleanLine = preg_replace('/^#?\s*\d+[\.\)]?\s*(\||-)?\s*/', '', $line);
                $cols = array_values(array_filter(array_map('trim', explode('|', $cleanLine)), fn($c) => $c !== ''));
                if (count($cols) >= 3 && preg_match('/^(?:#?\d+[\.\)]?|#)$/', $cols[0])) {
                    array_shift($cols);
                }
                if (count($cols) >= 4) {
                    $items[] = [
                        'nama_produk' => $cols[0],
                        'sku'         => $this->normalizeSku($cols[1]),
                        'variasi'     => $cols[2],
                        'qty'         => max(1, (int) preg_replace('/[^0-9]/', '', $cols[3])),
                    ];
                } elseif (count($cols) === 3) {
                    $items[] = [
                        'nama_produk' => $cols[0],
                        'sku'         => $this->normalizeSku($cols[1]),
                        'variasi'     => '',
                        'qty'         => max(1, (int) preg_replace('/[^0-9]/', '', $cols[2])),
                    ];
                }
            }
            return $items;
        }

        // 3. Check if table lines have numbered rows (e.g. "1 ...", "2 ...", "#1 ...")
        $hasRowNumbers = false;
        foreach ($tableLines as $tl) {
            if (preg_match('/^#?\s*\d+[\.\)]?\s+/i', $tl)) {
                $hasRowNumbers = true;
                break;
            }
        }

        if ($hasRowNumbers) {
            // Group into row blocks
            $blocks = [];
            $currentBlock = [];

            foreach ($tableLines as $tl) {
                if (preg_match('/^#?\s*\d+[\.\)]?\s+/i', $tl)) {
                    if (!empty($currentBlock)) {
                        $blocks[] = $currentBlock;
                    }
                    $currentBlock = [$tl];
                } else {
                    if (!empty($currentBlock)) {
                        $currentBlock[] = $tl;
                    } else {
                        $currentBlock = [$tl];
                    }
                }
            }
            if (!empty($currentBlock)) {
                $blocks[] = $currentBlock;
            }

            foreach ($blocks as $blk) {
                $item = $this->parseBlockLines($blk);
                if (!empty($item['nama_produk']) || !empty($item['sku'])) {
                    $items[] = $item;
                }
            }

            return $items;
        }

        // 4. Non-numbered single or multi-line items (fallback to line-by-line parsing)
        $pendingName = '';
        foreach ($tableLines as $cleanLine) {
            if (preg_match('/\t|\s{2,}/', $cleanLine)) {
                $cols = array_values(array_filter(preg_split('/(?:\t|\s{2,})/', $cleanLine), fn($c) => trim($c) !== ''));
                if (count($cols) >= 3 && preg_match('/^(?:#?\d+[\.\)]?|#)$/', $cols[0])) {
                    array_shift($cols);
                }

                if (count($cols) >= 4) {
                    $items[] = [
                        'nama_produk' => trim(($pendingName ? $pendingName . ' ' : '') . $cols[0]),
                        'sku'         => $this->normalizeSku($cols[1]),
                        'variasi'     => $cols[2],
                        'qty'         => max(1, (int) preg_replace('/[^0-9]/', '', $cols[3])),
                    ];
                    $pendingName = '';
                    continue;
                } elseif (count($cols) === 3 && $isTikTokHeader) {
                    // TikTok Shop: Product Name | SKU[Seller SKU] = variasi | Qty
                    $items[] = [
                        'nama_produk' => trim(($pendingName ? $pendingName . ' ' : '') . $cols[0]),
                        'sku'         => '',
                        'variasi'     => trim($cols[1]),
                        'qty'         => max(1, (int) preg_replace('/[^0-9]/', '', $cols[2])),
                    ];
                    $pendingName = '';
                    continue;
                } elseif (count($cols) === 3) {
                    if (preg_match('/^(?:Link\s+CO|[A-Z0-9_\-]+|\w+[\-_]\w+)/i', $cols[0]) && strlen($cols[0]) < 30) {
                        $items[] = [
                            'nama_produk' => $pendingName ?: '',
                            'sku'         => $this->normalizeSku($cols[0]),
                            'variasi'     => $cols[1],
                            'qty'         => max(1, (int) preg_replace('/[^0-9]/', '', $cols[2])),
                        ];
                        $pendingName = '';
                        continue;
                    } else {
                        $items[] = [
                            'nama_produk' => trim(($pendingName ? $pendingName . ' ' : '') . $cols[0]),
                            'sku'         => $this->normalizeSku($cols[1]),
                            'variasi'     => '',
                            'qty'         => max(1, (int) preg_replace('/[^0-9]/', '', $cols[2])),
                        ];
                        $pendingName = '';
                        continue;
                    }
                }
            }

            // Single space delimited
            if (preg_match('/^((?:Link\s+CO(?:\s+Live)?\s+[0-9\-]+|[A-Za-z0-9_\-]+(?:[\-_][A-Za-z0-9]+)*))\s+([A-Za-z0-9_\-\.\/]{1,15}(?:\s+[A-Za-z0-9]{1,10})?)\s+(\d{1,4})$/i', $cleanLine, $m)) {
                $items[] = [
                    'nama_produk' => $pendingName ?: '',
                    'sku'         => $this->normalizeSku($m[1]),
                    'variasi'     => trim($m[2]),
                    'qty'         => max(1, (int) $m[3]),
                ];
                $pendingName = '';
            } elseif (preg_match('/^(.*?)\s+((?:Link\s+CO(?:\s+Live)?\s+[0-9\-]+|PRD-[A-Z0-9\-]+|[A-Z0-9]{2,15}[\-_][A-Z0-9\-]+))\s+([A-Za-z0-9_\-\.\/]{1,15}(?:\s+[A-Za-z0-9]{1,10})?)\s+(\d{1,4})$/i', $cleanLine, $m)) {
                $items[] = [
                    'nama_produk' => trim(($pendingName ? $pendingName . ' ' : '') . $m[1]),
                    'sku'         => $this->normalizeSku($m[2]),
                    'variasi'     => trim($m[3]),
                    'qty'         => max(1, (int) $m[4]),
                ];
                $pendingName = '';
            } elseif (preg_match('/^(.*?)\s+([A-Za-z0-9_\-\.\/]{2,20})\s+(\d{1,4})$/', $cleanLine, $m) && strlen(trim($m[1])) >= 3) {
                $items[] = [
                    'nama_produk' => trim(($pendingName ? $pendingName . ' ' : '') . $m[1]),
                    'sku'         => $this->normalizeSku($m[2]),
                    'variasi'     => '',
                    'qty'         => max(1, (int) $m[3]),
                ];
                $pendingName = '';
            } elseif (preg_match('/^(.*?)\s+(\d{1,4})$/', $cleanLine, $m)) {
                $items[] = [
                    'nama_produk' => trim(($pendingName ? $pendingName . ' ' : '') . $m[1]),
                    'sku'         => '',
                    'variasi'     => '',
                    'qty'         => max(1, (int) $m[2]),
                ];
                $pendingName = '';
            } else {
                if (!empty($items)) {
                    $lastIdx = count($items) - 1;
                    $items[$lastIdx]['nama_produk'] .= ' ' . $cleanLine;
                } else {
                    $pendingName = $cleanLine;
                }
            }
        }

        return $items;
    }

    /**
     * Parse a multi-line product row block where columns wrap across multiple lines.
     */
    public function parseBlockLines(array $lines): array
    {
        $firstLine = $lines[0];
        // Strip leading row number (e.g. "1 ", "2. ", "#1 ")
        $firstLineClean = preg_replace('/^#?\s*\d+[\.\)]?\s*/', '', $firstLine);

        // Qty is at the end of the first line: \s+(\d{1,4})$
        $qty = 1;
        if (preg_match('/\s+(\d{1,4})$/', $firstLineClean, $qtyM)) {
            $qty = (int) $qtyM[1];
            $firstLineClean = preg_replace('/\s+\d{1,4}$/', '', $firstLineClean);
        }

        $nameParts = [];
        $skuParts = [];
        $varParts = [];

        // Case 1: Standalone SKU (e.g. Link CO 1-50 20)
        if (preg_match('/^((?:Link\s+CO(?:\s+Live)?\s+[0-9\-]+|[A-Za-z0-9_\-]+(?:[\-_][A-Za-z0-9]+)*))\s+([A-Za-z0-9_\-\.\/]{1,15}(?:\s+[A-Za-z0-9]{1,10})?)$/i', $firstLineClean, $m)) {
            $skuParts[] = trim($m[1]);
            $varParts[] = trim($m[2]);
        }
        // Case 2: Link CO with product name
        elseif (preg_match('/^(.*?)\s+((?:Link\s+CO(?:\s+Live)?\s+[0-9\-]+))\s*(.*?)$/i', $firstLineClean, $m)) {
            $nameParts[] = trim($m[1]);
            $skuParts[] = trim($m[2]);
            if (!empty($m[3])) $varParts[] = trim($m[3]);
        }
        // Case 3: Hyphenated or labeled SKU pattern
        elseif (preg_match('/^(.*?)\s+([a-z0-9_\-]+-[a-z0-9_\-]*)\s*(.*?)$/i', $firstLineClean, $m)) {
            $nameParts[] = trim($m[1]);
            $skuParts[] = trim($m[2]);
            if (!empty($m[3])) $varParts[] = trim($m[3]);
        } else {
            // No obvious SKU on line 0
            $nameParts[] = $firstLineClean;
        }

        // Parse continuation lines
        for ($i = 1; $i < count($lines); $i++) {
            $sub = trim($lines[$i]);
            if (empty($sub)) continue;

            // Check if line contains a hyphenated SKU segment
            if (preg_match('/^(.*?)\s+([a-z0-9_\-]+-[a-z0-9_\-]*)\s*(.*?)$/i', $sub, $m)) {
                if (!empty($m[1])) $nameParts[] = trim($m[1]);
                if (!empty($m[2])) $skuParts[] = trim($m[2]);
                if (!empty($m[3])) $varParts[] = trim($m[3]);
            } elseif (preg_match('/^(.*?)\s+([a-z0-9_\-]+-[a-z0-9_\-]*)$/i', $sub, $m)) {
                if (!empty($m[1])) $nameParts[] = trim($m[1]);
                if (!empty($m[2])) $skuParts[] = trim($m[2]);
            } elseif (preg_match('/^(.*?)\s+([A-Za-z0-9]{1,4})$/', $sub, $m)) {
                $token = trim($m[2]);
                $lead = trim($m[1]);
                $lastSku = end($skuParts);

                // Check if token is NOT an age specifier (e.g. 2th, 3th, 5th, 1thn, 6bln)
                $isAgeSpec = (bool) preg_match('/^\d+(?:th|thn|bln)$/i', $token);

                if (!$isAgeSpec && $lastSku && str_ends_with($lastSku, '-')) {
                    // If previous SKU part ends with hyphen, this token completes the SKU (e.g. putihvariasiabu- + L)
                    if (!empty($lead)) $nameParts[] = $lead;
                    $skuParts[] = $token;
                } elseif (!$isAgeSpec && preg_match('/^(?:[SMLX]|XS|XL|XXL|XXXL|[2-5]XL|[0-9]{1,2})$/i', $token) && !empty($skuParts)) {
                    if (!empty($lead)) $nameParts[] = $lead;
                    $varParts[] = $token;
                } else {
                    // Whole line belongs to product name
                    $nameParts[] = $sub;
                }
            } else {
                // Just continuation of product name
                $nameParts[] = $sub;
            }
        }

        // Reconstruct product name: handle words broken across lines
        $fullName = implode(' ', $nameParts);
        $fullName = preg_replace('/\s+/', ' ', $fullName);
        $fullName = preg_replace('/\b(\w+)\s+([a-z]{1,3})\b(?=\s+\d|\s+Anak|\s+Jogja|$)/', '$1$2', $fullName);
        $fullName = str_replace(['B arong', '2t h'], ['Barong', '2th'], $fullName);

        // Reconstruct SKU: stitch hyphenated fragments
        $fullSku = '';
        foreach ($skuParts as $sp) {
            if ($fullSku === '') {
                $fullSku = $sp;
            } elseif (str_ends_with($fullSku, '-')) {
                $fullSku .= $sp;
            } else {
                $fullSku .= '-' . $sp;
            }
        }

        // Reconstruct Variasi
        $fullVar = implode(' ', $varParts);
        $fullVar = trim(preg_replace('/\s*,\s*/', ', ', $fullVar));
        $fullVar = trim($fullVar, ', ');

        return [
            'nama_produk' => trim($fullName),
            'sku'         => $this->normalizeSku($fullSku),
            'variasi'     => trim($fullVar),
            'qty'         => $qty
        ];
    }

    /**
     * Normalize and clean SKU extracted from OCR.
     * Fixes common OCR noise like PRO- -> PRD-, PR0- -> PRD-, o01 -> 001.
     */
    public function normalizeSku(string $sku): string
    {
        $clean = trim($sku);
        if ($clean === '' || $clean === '-') {
            return '';
        }

        // Fix OCR typo in prefix (e.g. PRO-RAW-001 -> PRD-RAW-001, PR0- -> PRD-, PBD- -> PRD-, PAD- -> PRD-)
        $clean = preg_replace('/^(?:PR[O0]|PB[DO0]|PA[DO0])[-_]/i', 'PRD-', $clean);

        // Fix OCR typo in category codes (e.g. PRD-RAN-009 -> PRD-RAW-009)
        $clean = preg_replace('/^PRD[-_]RAN[-_]/i', 'PRD-RAW-', $clean);

        // Fix OCR typo in number suffix (e.g. oo1 -> 001, o01 -> 001)
        $clean = preg_replace('/[-_][oO]{2}(\d)$/', '-00$1', $clean);
        $clean = preg_replace('/[-_][oO](\d{2})$/', '-0$1', $clean);

        // Strip trailing orphan hyphens/underscores (e.g. SHOPEE- -> SHOPEE)
        $clean = rtrim($clean, "-_ \t");

        return $clean;
    }

    /**
     * Cleanly parse Indonesian currency amounts (handling unformatted numbers like Rp41040 and dotted Rp 41.040).
     */
    public function parseCurrency(string $val): float
    {
        $val = trim($val);
        $val = strtr($val, ['I' => '1', 'l' => '1', '|' => '1', 'O' => '0', 'o' => '0']);
        $val = preg_replace('/[,.]00$/', '', $val);
        $clean = preg_replace('/[^0-9]/', '', $val);
        return !empty($clean) ? (float) $clean : 0.0;
    }

    /**
     * Extract all cities appearing on a line (useful for multi-column horizontal labels).
     */
    public function extractAllCities(string $line): array
    {
        if (preg_match_all('/(?:KOTA|KAB\.?|KABUPATEN)\s+([^,\|\n]+?)(?=\s+(?:KOTA|KAB\.?|KABUPATEN)|,\s*JAWA|,\s*SUMATERA|,\s*INDONESIA|$)/i', $line, $m)) {
            $cities = [];
            foreach ($m[1] as $c) {
                $clean = ucwords(strtolower(trim($c)));
                if (!in_array(strtoupper($clean), ['INDONESIA', 'KAB', 'KOTA', 'KABUPATEN'], true)) {
                    $cities[] = $clean;
                }
            }
            return $cities;
        }
        return [];
    }

    /**
     * Extract plain text from PDF file by uncompressing FlateDecode streams and reading text blocks.
     */
    public function extractTextFromPdf(string $pdfPath): string
    {
        if (!file_exists($pdfPath)) {
            return '';
        }
        $content = (string) @file_get_contents($pdfPath);
        if (empty($content)) {
            return '';
        }

        $allText = '';

        // 1. Extract and decompress stream objects
        if (preg_match_all('/stream[\r\n]+(.*?)[\r\n]+endstream/s', $content, $streamMatches)) {
            $pageNum = 1;
            foreach ($streamMatches[1] as $stream) {
                $data = '';
                // Try decompressing FlateDecode stream
                $uncompressed = @gzuncompress($stream);
                if ($uncompressed !== false) {
                    $data = $uncompressed;
                } else {
                    $inflated = @gzinflate($stream);
                    if ($inflated !== false) {
                        $data = $inflated;
                    } else {
                        $data = $stream;
                    }
                }

                // Extract text operators: (Text) Tj, [(T) (ext)] TJ
                $pageText = '';
                if (preg_match_all('/\((.*?)\)\s*Tj/s', $data, $tjMatches)) {
                    $pageText .= ' ' . implode(' ', $tjMatches[1]);
                }
                if (preg_match_all('/\[(.*?)\]\s*TJ/s', $data, $arrayTjMatches)) {
                    foreach ($arrayTjMatches[1] as $tj) {
                        if (preg_match_all('/\((.*?)\)/s', $tj, $inner)) {
                            $pageText .= ' ' . implode('', $inner[1]);
                        }
                    }
                }

                if (strlen(trim($pageText)) > 20) {
                    $allText .= "\n--- PAGE {$pageNum} ---\n" . $pageText . "\n";
                    $pageNum++;
                }
            }
        }

        // 2. If no digital text streams found or too short, run automatic server-side OCR (renders pages + pure PHP barcode + Node OCR)
        if (empty($allText) || strlen(trim($allText)) < 50) {
            $ocrText = $this->extractTextFromPdfOcr($pdfPath);
            if (!empty($ocrText)) {
                $allText = $ocrText;
            }
        }

        // 3. Fallback: only search raw printable ASCII if OCR is not available and no text found
        if (empty($allText)) {
            if (preg_match_all('/[A-Za-z0-9_\-\.\:\/\,\(\)\s]{5,}/', $content, $asciiMatches)) {
                $filtered = array_filter($asciiMatches[0], function($str) {
                    return strlen(trim($str)) >= 5 && preg_match('/(?:Resi|AWB|SPX|J&T|JNE|Shopee|TikTok|Penerima|Pengirim|COD)/i', $str);
                });
                if (!empty($filtered)) {
                    $allText = implode("\n", $filtered);
                }
            }
        }

        return trim($allText);
    }

    /**
     * Automatic Server-Side OCR for multi-page thermal vector/image PDFs.
     * Uses PyMuPDF to render pages + PHP Code 128 barcode decode + Node Tesseract OCR.
     *
     * @param string $pdfPath
     * @return string
     */
    /**
     * Resolve an executable binary from a list of candidate absolute paths or names.
     * Returns the first candidate that exists or is found in PATH.
     */
    private function findBinary(string $name, array $candidates): string
    {
        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }
        // Fallback to bare name (relies on PATH)
        return $name;
    }

    public function extractTextFromPdfOcr(string $pdfPath): string
    {
        if (!file_exists($pdfPath)) {
            return '';
        }

        $writePath = defined('WRITEPATH') ? WRITEPATH : dirname(__DIR__, 3) . '/writable/';
        $tempDir = rtrim($writePath, '/\\') . '/temp_ocr_' . bin2hex(random_bytes(6));
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0777, true);
        }

        // Resolve absolute paths for python and node so they work from web server context (Laragon)
        $pythonBin = $this->findBinary('python', [
            'C:\\Users\\Lenovo\\AppData\\Local\\Programs\\Python\\Python313\\python.exe',
            'D:\\1\\python.exe',
            'C:\\Python313\\python.exe',
            'C:\\Python312\\python.exe',
            'C:\\Python311\\python.exe',
            'C:\\Python310\\python.exe',
        ]);
        $nodeBin = $this->findBinary('node', [
            'D:\\nodejs\\node.exe',
            'C:\\Program Files\\nodejs\\node.exe',
            'C:\\nodejs\\node.exe',
        ]);

        if (function_exists('log_message')) {
            log_message('debug', 'extractTextFromPdfOcr: pythonBin=' . $pythonBin . ' nodeBin=' . $nodeBin . ' tempDir=' . $tempDir);
        }

        try {
            $escapedPdf = escapeshellarg(str_replace('/', DIRECTORY_SEPARATOR, $pdfPath));
            $escapedDir = escapeshellarg(str_replace('/', DIRECTORY_SEPARATOR, $tempDir));

            // 1. Render all PDF pages to PNGs via Python PyMuPDF
            $pyScript = "import pymupdf, sys; doc=pymupdf.open(sys.argv[1]); [p.get_pixmap(dpi=150).save(f'{sys.argv[2]}/page_{i+1}.png') for i, p in enumerate(doc)]";
            $pyCmd = escapeshellarg($pythonBin) . " -c \"{$pyScript}\" {$escapedPdf} {$escapedDir}";
            $pyOutput = [];
            @exec($pyCmd . ' 2>&1', $pyOutput);
            if (function_exists('log_message')) {
                log_message('debug', 'extractTextFromPdfOcr: pyCmd result=' . implode(' | ', $pyOutput));
            }

            $pageFiles = glob($tempDir . '/page_*.png');
            if (function_exists('log_message')) {
                log_message('debug', 'extractTextFromPdfOcr: pageFiles count=' . count($pageFiles ?? []));
            }
            if (empty($pageFiles)) {
                return '';
            }

            // Sort page files numerically
            usort($pageFiles, function($a, $b) {
                preg_match('/page_(\d+)\.png$/', $a, $ma);
                preg_match('/page_(\d+)\.png$/', $b, $mb);
                return ((int)($ma[1] ?? 0)) <=> ((int)($mb[1] ?? 0));
            });

            // 2. Decode barcodes directly from rendered pages using PHP Code 128
            $barcodes = [];
            foreach ($pageFiles as $pf) {
                $base = basename($pf);
                $bc = $this->decodeCode128FromImage($pf);
                if (!empty($bc)) {
                    $barcodes[$base] = $bc;
                }
            }

            // 3. Run Node OCR script on the pages
            $ocrScript = __DIR__ . '/ocr_pages.js';
            if (file_exists($ocrScript)) {
                $nodeOutput = [];
                $nodeCmd = escapeshellarg($nodeBin) . " " . escapeshellarg($ocrScript) . " " . $escapedDir;
                if (function_exists('log_message')) {
                    log_message('debug', 'extractTextFromPdfOcr: nodeCmd=' . $nodeCmd);
                }
                @exec($nodeCmd . ' 2>&1', $nodeOutput);
                if (function_exists('log_message')) {
                    log_message('debug', 'extractTextFromPdfOcr: nodeCmd result=' . implode(' | ', $nodeOutput));
                }
            }

            $jsonFile = $tempDir . '/ocr_results.json';
            $ocrResults = [];
            if (file_exists($jsonFile)) {
                $ocrResults = (array) @json_decode(file_get_contents($jsonFile), true);
            }
            if (function_exists('log_message')) {
                log_message('debug', 'extractTextFromPdfOcr: jsonFile exists=' . (file_exists($jsonFile) ? 'yes' : 'no') . ' ocrResults count=' . count($ocrResults));
            }

            // 4. Combine into standardized batch text format
            $combinedText = '';
            $pNum = 1;
            foreach ($pageFiles as $pf) {
                $base = basename($pf);
                $bc = $barcodes[$base] ?? '';
                $ocrText = trim($ocrResults[$base] ?? '');

                $combinedText .= "\n--- PAGE {$pNum} ---\n";
                if (!empty($bc)) {
                    $combinedText .= "No. Resi: {$bc}\n";
                }
                if (!empty($ocrText)) {
                    $combinedText .= $ocrText . "\n";
                }
                $pNum++;
            }

            return trim($combinedText);
        } catch (\Throwable $e) {
            if (function_exists('log_message')) {
                log_message('error', 'extractTextFromPdfOcr error: ' . $e->getMessage());
            }
            return '';
        } finally {
            if (is_dir($tempDir)) {
                $files = glob($tempDir . '/*');
                foreach ($files as $f) {
                    @unlink($f);
                }
                @rmdir($tempDir);
            }
        }
    }

    /**
     * Extract text or barcode from an uploaded image (using built-in PHP GD Code 128 decoder).
     * No Python required.
     *
     * @param string $imagePath
     * @return string
     */
    public function extractTextFromImage(string $imagePath): string
    {
        if (!file_exists($imagePath)) {
            return '';
        }

        $barcode = $this->decodeCode128FromImage($imagePath);
        if (!empty($barcode)) {
            return "No. Resi: {$barcode}\n";
        }

        return '';
    }

    /**
     * Pure PHP Code 128 Barcode Decoder using standard GD extension.
     * Fast (< 5ms), 100% deterministic, zero external binaries.
     *
     * @param string $imagePath
     * @return string|null
     */
    public function decodeCode128FromImage(string $imagePath): ?string
    {
        if (!file_exists($imagePath) || !function_exists('imagecreatefromstring')) {
            return null;
        }

        $content = @file_get_contents($imagePath);
        if (!$content) {
            return null;
        }

        $im = @imagecreatefromstring($content);
        if (!$im) {
            return null;
        }

        $w = imagesx($im);
        $h = imagesy($im);
        if ($w < 50 || $h < 20) {
            imagedestroy($im);
            return null;
        }

        $patterns = [
            "212222", "222122", "222221", "121223", "121322", "131222", "122213", "122312", "132212", "221213",
            "221312", "231212", "112232", "122132", "122231", "113222", "123122", "123221", "223211", "221132",
            "221231", "213212", "223112", "312131", "311222", "321122", "321221", "312212", "322112", "322211",
            "212123", "212321", "232121", "111323", "131123", "131321", "112313", "132113", "132311", "211313",
            "231113", "231311", "112133", "112331", "132131", "113123", "113321", "133121", "313121", "211331",
            "231131", "213113", "213311", "213131", "311123", "311321", "331121", "312113", "312311", "332111",
            "314111", "221411", "431111", "111224", "111422", "121124", "121421", "141122", "141221", "112214",
            "112412", "122114", "122411", "142112", "142211", "241211", "221114", "413111", "241112", "134111",
            "111242", "121142", "121241", "114212", "124112", "124211", "411212", "421112", "421211", "212141",
            "214121", "412121", "111143", "111341", "131141", "114113", "114311", "411113", "411311", "113141",
            "114131", "311141", "411131", "211412", "211214", "211232", "2331112"
        ];
        $patternMap = array_flip($patterns);

        // Sample Y positions (prioritize upper 8% to 35% where 1D barcodes reside, then middle 38% to 70%)
        $sampleY = [];
        for ($pct = 0.08; $pct <= 0.35; $pct += 0.02) {
            $sampleY[] = (int) round($h * $pct);
        }
        for ($pct = 0.38; $pct <= 0.70; $pct += 0.04) {
            $sampleY[] = (int) round($h * $pct);
        }

        $decodedText = null;

        foreach ($sampleY as $y) {
            if ($y < 0 || $y >= $h) continue;

            $lum = [];
            $minL = 255;
            $maxL = 0;
            $sumL = 0;
            for ($x = 0; $x < $w; $x++) {
                $rgb = imagecolorat($im, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $l = (int) (0.299 * $r + 0.587 * $g + 0.114 * $b);
                $lum[] = $l;
                if ($l < $minL) $minL = $l;
                if ($l > $maxL) $maxL = $l;
                $sumL += $l;
            }

            if (($maxL - $minL) < 50) continue;
            $mid = (int) round(($minL + $maxL) / 2);
            $avg = (int) round($sumL / $w);

            foreach ([$mid, (int) round($mid * 0.9), (int) round($mid * 1.1), $avg, 120] as $th) {
                $res = $this->decodeCode128Row($lum, $th, $patterns, $patternMap);
                if ($res && !empty($res['text'])) {
                    $trk = trim($res['text']);
                    if (preg_match('/^(?:SPX|JP|JT|JX|JZ|JNE|POS|SOC|LP|ID\d|NLID|TKP|IDE|IDS|\d{10,})/i', $trk)) {
                        $decodedText = $trk;
                        break 2;
                    }
                }
            }
        }

        imagedestroy($im);
        return $decodedText;
    }

    private function decodeCode128Row(array $rowLuminance, int $threshold, array $patterns, array $patternMap): ?array
    {
        $bars = [];
        $currentIsBlack = null;
        $currentLen = 0;
        $len = count($rowLuminance);

        for ($x = 0; $x < $len; $x++) {
            $isBlack = ($rowLuminance[$x] < $threshold);
            if ($currentIsBlack === null) {
                $currentIsBlack = $isBlack;
                $currentLen = 1;
            } elseif ($isBlack === $currentIsBlack) {
                $currentLen++;
            } else {
                $bars[] = ['black' => $currentIsBlack, 'len' => $currentLen];
                $currentIsBlack = $isBlack;
                $currentLen = 1;
            }
        }
        $bars[] = ['black' => $currentIsBlack, 'len' => $currentLen];

        $barCount = count($bars);
        for ($startBarIdx = 0; $startBarIdx < $barCount - 20; $startBarIdx++) {
            if (!$bars[$startBarIdx]['black']) continue;

            $startChunk = array_slice($bars, $startBarIdx, 6);
            if (count($startChunk) < 6) continue;

            $startTotPx = array_sum(array_column($startChunk, 'len'));
            $mSize = $startTotPx / 11.0;
            if ($mSize < 0.8 || $mSize > 35.0) continue;

            $startPat = '';
            foreach ($startChunk as $b) {
                $modCount = (int) round($b['len'] / $mSize);
                if ($modCount < 1) $modCount = 1;
                if ($modCount > 4) $modCount = 4;
                $startPat .= $modCount;
            }

            if (!isset($patternMap[$startPat])) continue;
            $startSym = $patternMap[$startPat];
            if ($startSym !== 103 && $startSym !== 104 && $startSym !== 105) continue;

            $symbols = [$startSym];
            $idx = $startBarIdx + 6;
            $foundStop = false;

            while ($idx < $barCount) {
                if ($idx + 7 <= $barCount) {
                    $stopChunk = array_slice($bars, $idx, 7);
                    $stopTotPx = array_sum(array_column($stopChunk, 'len'));
                    $stopMSize = $stopTotPx / 13.0;
                    $stopPat = '';
                    foreach ($stopChunk as $b) {
                        $mc = (int) round($b['len'] / $stopMSize);
                        if ($mc < 1) $mc = 1;
                        if ($mc > 4) $mc = 4;
                        $stopPat .= $mc;
                    }
                    if ($stopPat === "2331112") {
                        $symbols[] = 106;
                        $foundStop = true;
                        break;
                    }
                }

                $chunk = array_slice($bars, $idx, 6);
                if (count($chunk) < 6) break;

                $totPx = array_sum(array_column($chunk, 'len'));
                $symMSize = $totPx / 11.0;
                $pat = '';
                foreach ($chunk as $b) {
                    $mc = (int) round($b['len'] / $symMSize);
                    if ($mc < 1) $mc = 1;
                    if ($mc > 4) $mc = 4;
                    $pat .= $mc;
                }

                if (isset($patternMap[$pat])) {
                    $sym = $patternMap[$pat];
                } else {
                    $bestSym = -1;
                    $bestDiff = 999;
                    for ($s = 0; $s < 106; $s++) {
                        $p = $patterns[$s];
                        $diff = 0;
                        for ($k = 0; $k < 6; $k++) {
                            $diff += abs((int)$p[$k] - (int)$pat[$k]);
                        }
                        if ($diff < $bestDiff) {
                            $bestDiff = $diff;
                            $bestSym = $s;
                        }
                    }
                    $sym = ($bestDiff <= 2) ? $bestSym : -1;
                }

                if ($sym < 0) break;
                $symbols[] = $sym;
                $idx += 6;
            }

            if ($foundStop && count($symbols) >= 4) {
                $checksumIndex = count($symbols) - 2;
                $sum = $symbols[0];
                for ($i = 1; $i < $checksumIndex; $i++) {
                    $sum += ($symbols[$i] * $i);
                }
                $expectedChecksum = $sum % 103;

                $mode = $symbols[0];
                $text = '';
                for ($i = 1; $i < $checksumIndex; $i++) {
                    $s = $symbols[$i];
                    if ($s === 100) { $mode = 104; continue; }
                    if ($s === 99)  { $mode = 105; continue; }
                    if ($s === 101) { $mode = 103; continue; }

                    if ($mode === 104 || $mode === 103) {
                        $text .= chr(32 + $s);
                    } elseif ($mode === 105) {
                        $text .= sprintf('%02d', $s);
                    }
                }

                if (strlen($text) >= 6) {
                    return [
                        'text'          => $text,
                        'checksumValid' => ($symbols[$checksumIndex] === $expectedChecksum)
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Parse batch text that may contain multiple shipping labels (from multi-page PDF or combined text).
     *
     * @param string $rawText
     * @return array [ 'is_batch' => bool, 'count' => int, 'items' => array ]
     */
    public function parseBatch(string $rawText): array
    {
        $text = str_replace(["\x00", "\r\n", "\r"], ["", "\n", "\n"], $rawText);
        $chunks = [];

        // Strategy A: Split by explicit page markers (from PDF.js or extractTextFromPdf)
        if (preg_match('/--- PAGE \d+ ---|\f/', $text)) {
            $rawChunks = preg_split('/(?:--- PAGE \d+ ---|\f)/', $text);
            foreach ($rawChunks as $c) {
                if (strlen(trim($c)) >= 25) {
                    $chunks[] = trim($c);
                }
            }
        }

        // Strategy B: If no page markers or only 1 chunk, try splitting by label headers / tracking markers
        if (count($chunks) <= 1) {
            $candidateChunks = preg_split('/(?=(?:\n\s*(?:No\.?\s*Resi|Resi|AWB|Tracking\s*(?:No|Number))\s*[:\|\-;\s]))/i', $text);
            if (count($candidateChunks) > 1) {
                $validChunks = [];
                foreach ($candidateChunks as $cc) {
                    if (strlen(trim($cc)) >= 25) {
                        $validChunks[] = trim($cc);
                    }
                }
                if (count($validChunks) > 1) {
                    $chunks = $validChunks;
                }
            }
        }

        // Strategy C: If still only 1 chunk, treat as single text
        if (empty($chunks)) {
            $chunks = [$text];
        }

        $items = [];
        $seen = [];

        foreach ($chunks as $chunk) {
            $parsed = $this->parseFull($chunk);
            $trk = $parsed['tracking_number'] ?? '';
            $orderId = $parsed['no_pesanan'] ?? '';

            // Unique key to prevent duplicates
            $key = !empty($trk) ? $trk : $orderId;
            if (!empty($key)) {
                if (!isset($seen[$key])) {
                    $seen[$key] = true;
                    // Keep if valid label
                    if ($parsed['courier'] !== 'UNKNOWN' || !empty($trk) || !empty($orderId)) {
                        $items[] = $parsed;
                    }
                }
            } elseif ($parsed['courier'] !== 'UNKNOWN' && !empty($parsed['recipient_name']) && $parsed['recipient_name'] !== 'Pelanggan Resi') {
                $items[] = $parsed;
            }
        }

        // If items empty, fallback to parsing entire text as 1 item
        if (empty($items)) {
            $items = [$this->parseFull($text)];
        }

        return [
            'is_batch' => count($items) > 1,
            'count'    => count($items),
            'items'    => $items,
        ];
    }

    private function normalizeTrackingNumber(string $tracking, string $courier = 'UNKNOWN'): string
    {
        $clean = preg_replace('/\s+/', '', $tracking);
        $clean = trim($clean, " \t\n\r\0\x0B:;|-[]()|/\\_");

        // Reject if candidate contains no digits at all (e.g. SPXPENERIMA, SPXEXPRESS, PENERIMA)
        if (!preg_match('/[0-9]/', $clean)) {
            return '';
        }

        // Exclude common label words that might get glued to a prefix
        if (preg_match('/^(?:PENERIMA|PENGIRIM|EXPRESS|STANDARD|HEMAT|SAMEDAY|INSTANT|ALAMAT|KOTA|ONGKIR|PRODUK|VARIASI|JUMLAH)/i', $clean)) {
            return '';
        }
        if (preg_match('/(?:PENERIMA|PENGIRIM|EXPRESS|STANDARD|HEMAT|SAMEDAY|INSTANT|ALAMAT|KOTA)$/i', $clean)) {
            return '';
        }

        // Normalize SPX prefix variations: SPX-1D, SPX_ID, SPXID_, SPX-ID-, SPX|D, SPXlD
        $clean = preg_replace('/^SPX[-_]?(?:[\|1Il]?D|ID)[-_]?/i', 'SPXID', $clean);

        if (preg_match('/^SPXID([0-9O]{8,15}[A-Z0-9]{0,4})$/i', $clean, $m)) {
            $digits = str_replace('O', '0', strtoupper($m[1]));
            return 'SPXID' . $digits;
        }

        if (preg_match('/^SPX[-_]?([0-9O]{8,18}[A-Z0-9]{0,4})$/i', $clean, $m)) {
            $digits = str_replace('O', '0', strtoupper($m[1]));
            return 'SPX' . $digits;
        }

        if (preg_match('/^[\|1Il]?D([0-9O]{9,15}[A-Z0-9]{0,4})$/i', $clean, $m)) {
            $digits = str_replace('O', '0', strtoupper($m[1]));
            return 'ID' . $digits;
        }

        // J&T: JP/JT/JX/JZ + digits
        if (preg_match('/^(J[PTXZ]|JNT)([0-9O]{9,14})$/i', $clean, $m)) {
            $digits = str_replace('O', '0', strtoupper($m[2]));
            return strtoupper($m[1]) . $digits;
        }

        // Must have at least 6 digits to be considered a tracking number
        if (preg_match_all('/[0-9]/', $clean) < 6) {
            return '';
        }

        return strtoupper($clean);
    }

    private function normalizePhone(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($clean, '62')) {
            $clean = '0' . substr($clean, 2);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '0' . $clean;
        }
        return $clean;
    }

    private function extractCityFromText(string $text): ?string
    {
        if (preg_match('/(?:KAB\.?|KABUPATEN|KOTA)\s+([A-Za-z\s]{3,35})/i', $text, $m)) {
            $raw = trim($m[1]);
            // Take only first segment before multiple spaces, pipes, commas, or line breaks
            $parts = preg_split('/[\t\r\n,\|\/]+|\s{2,}/', $raw);
            $matched = trim($parts[0] ?? $raw);
            // Strip trailing province words
            $matched = preg_replace('/\s+(JAWA|SUMATERA|KALIMANTAN|SULAWESI|BALI|NTB|NTT|PAPUA|BARAT|TIMUR|TENGAH|SELATAN|UTARA).*/i', '', $matched);
            $clean = ucwords(strtolower(trim($matched)));
            if (strlen($clean) >= 3 && !in_array(strtoupper($clean), ['INDONESIA', 'KAB', 'KOTA', 'KABUPATEN'], true)) {
                return $clean;
            }
        }
        return null;
    }

    private function detectCity(string $text): ?string
    {
        $cities = [
            'Jakarta', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Jogja', 'Sleman', 'Bantul',
            'Kulon Progo', 'Gunungkidul', 'Surakarta', 'Solo', 'Magelang', 'Malang', 'Tangerang',
            'Bekasi', 'Depok', 'Bogor', 'Medan', 'Palembang', 'Makassar', 'Denpasar', 'Balikpapan',
            'Samarinda', 'Banjarmasin', 'Pontianak', 'Manado', 'Bandar Lampung', 'Pekanbaru', 'Padang',
            'Cirebon', 'Bengkulu', 'Tegal', 'Pekalongan', 'Cilacap', 'Purwokerto', 'Banyumas', 'Kudus',
            'Pati', 'Jepara', 'Brebes', 'Kendal', 'Batang', 'Pemalang', 'Demak', 'Salatiga', 'Sukoharjo',
            'Klaten', 'Boyolali', 'Karanganyar', 'Wonogiri', 'Sragen', 'Grobogan', 'Blora', 'Rembang',
            'Temanggung', 'Wonosobo', 'Purworejo', 'Kebumen', 'Banjarnegara', 'Purbalingga', 'Tasikmalaya',
            'Ciamis', 'Garut', 'Sumedang', 'Cianjur', 'Sukabumi', 'Subang', 'Purwakarta', 'Karawang',
            'Indramayu', 'Majalengka', 'Kuningan', 'Cimahi', 'Sidoarjo', 'Gresik', 'Mojokerto', 'Jombang',
            'Bojonegoro', 'Tuban', 'Lamongan', 'Madiun', 'Ngawi', 'Magetan', 'Ponorogo', 'Pacitan',
            'Kediri', 'Nganjuk', 'Blitar', 'Tulungagung', 'Trenggalek', 'Pasuruan', 'Probolinggo',
            'Lumajang', 'Bondowoso', 'Situbondo', 'Jember', 'Banyuwangi', 'Bangkalan', 'Sampang',
            'Pamekasan', 'Sumenep', 'Banda Aceh', 'Pematangsiantar', 'Sibolga', 'Bukittinggi', 'Dumai',
            'Jambi', 'Prabumulih', 'Lubuklinggau', 'Metro', 'Pangkal Pinang', 'Batam', 'Tanjung Pinang',
            'Singkawang', 'Banjarbaru', 'Palangka Raya', 'Bontang', 'Tarakan', 'Bitung', 'Tomohon',
            'Kotamobagu', 'Palu', 'Parepare', 'Palopo', 'Kendari', 'Baubau', 'Gorontalo', 'Ambon',
            'Ternate', 'Jayapura', 'Sorong', 'Mataram', 'Bima', 'Kupang',
            'Badung', 'Gianyar', 'Tabanan', 'Buleleng', 'Klungkung', 'Karangasem', 'Jembrana', 'Bangli'
        ];

        foreach ($cities as $c) {
            if (preg_match('/\b' . preg_quote($c, '/') . '\b/i', $text)) {
                return $c;
            }
        }
        return null;
    }

    /**
     * Extract shipping deadline ("Batas Kirim", "Kirim Sebelum", "Batas Pengiriman", etc.)
     * from label text and normalize to standard HTML5 YYYY-MM-DD format.
     *
     * @param string $text
     * @return string|null Formatted as YYYY-MM-DD or null if not found
     */
    public function extractBatasKirim(string $text): ?string
    {
        $patterns = [
            // 1. Batas Kirim / Batas Pengiriman / Kirim Sebelum / Ship by date / In transit by / Batas Waktu
            '/(?:Batas\s*(?:K[il1]r[il1]m|Peng[il1]r[il1]man|Waktu(?:\s*K[il1]r[il1]m)?)|K[il1]r[il1]m\s*Sebe[il1]um|Ship\s*by(?:\s*date)?|In\s*transit\s*by|Deadline(?:\s*K[il1]r[il1]m)?|Tgl\.?\s*(?:K[il1]r[il1]m|Peng[il1]r[il1]man|Batas))\s*[:\|\-;\s]*([0-9]{1,4}\s*[\-\/\.]\s*[0-9]{1,2}\s*[\-\/\.]\s*[0-9]{2,4}|[0-9]{1,2}\s*[\-\s\/]\s*[A-Za-z]{3,10}\s*[\-\s\/]\s*[0-9]{2,4})/i',

            // 2. Multiline Batas Kirim (label on one line, date on the next line)
            '/(?:Batas\s*(?:K[il1]r[il1]m|Peng[il1]r[il1]man)|K[il1]r[il1]m\s*Sebe[il1]um|In\s*transit\s*by)\s*[:\|\-;\s]*[\r\n]+\s*([0-9]{1,4}\s*[\-\/\.]\s*[0-9]{1,2}\s*[\-\/\.]\s*[0-9]{2,4}|[0-9]{1,2}\s*[\-\s\/]\s*[A-Za-z]{3,10}\s*[\-\s\/]\s*[0-9]{2,4})/i',

            // 3. Fallback: Tanggal Pesanan / Tgl Pesanan / Tanggal Order / Ship date
            '/(?:Tanggal\s*(?:Pesanan|Order|Transaksi)?|Tgl\.?\s*(?:Pesanan|Order|Transaksi)|Ship\s*Date)\s*[:\|\-;\s]*([0-9]{1,4}\s*[\-\/\.]\s*[0-9]{1,2}\s*[\-\/\.]\s*[0-9]{2,4}|[0-9]{1,2}\s*[\-\s\/]\s*[A-Za-z]{3,10}\s*[\-\s\/]\s*[0-9]{2,4})/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $normalized = $this->normalizeDate($matches[1]);
                if ($normalized !== null) {
                    return $normalized;
                }
            }
        }

        return null;
    }

    /**
     * Normalize various date formats (DD-MM-YYYY, DD/MM/YYYY, DD Month YYYY, YYYY-MM-DD)
     * to strict HTML5 date format YYYY-MM-DD.
     *
     * @param string $rawDate
     * @return string|null Formatted as YYYY-MM-DD or null if invalid
     */
    public function normalizeDate(string $rawDate): ?string
    {
        $clean = trim($rawDate);
        $clean = preg_replace('/\s+/', ' ', $clean);

        $monthMap = [
            'jan' => 1, 'januari' => 1, 'january' => 1,
            'feb' => 2, 'februari' => 2, 'february' => 2,
            'mar' => 3, 'maret' => 3, 'march' => 3,
            'apr' => 4, 'april' => 4,
            'mei' => 5, 'may' => 5,
            'jun' => 6, 'juni' => 6, 'june' => 6,
            'jul' => 7, 'juli' => 7, 'july' => 7,
            'agu' => 8, 'agustus' => 8, 'aug' => 8, 'august' => 8,
            'sep' => 9, 'september' => 9,
            'okt' => 10, 'oktober' => 10, 'oct' => 10, 'october' => 10,
            'nop' => 11, 'nov' => 11, 'november' => 11,
            'des' => 12, 'desember' => 12, 'dec' => 12, 'december' => 12,
        ];

        // Pattern 1: Named month, e.g. '28 Mar 2024', '28 Maret 2024', '28-Mar-2024'
        if (preg_match('/^([0-9]{1,2})\s*[\-\s\/]\s*([A-Za-z]{3,10})\s*[\-\s\/]\s*([0-9]{2,4})/i', $clean, $m)) {
            $d = (int) $m[1];
            $monKey = strtolower(substr($m[2], 0, 3));
            $mon = $monthMap[$monKey] ?? null;
            $y = (int) $m[3];
            if ($y < 100) $y += 2000;
            if ($mon && checkdate($mon, $d, $y)) {
                return sprintf('%04d-%02d-%02d', $y, $mon, $d);
            }
        }

        // Pattern 2: Numeric formats e.g. 28-03-2024, 28/03/2024, 2024-03-28
        if (preg_match('/^([0-9]{1,4})\s*[\-\/\.\s]\s*([0-9]{1,2})\s*[\-\/\.\s]\s*([0-9]{2,4})/', $clean, $m)) {
            $p1 = (int) $m[1];
            $p2 = (int) $m[2];
            $p3 = (int) $m[3];

            // Format: YYYY-MM-DD
            if ($p1 >= 1000) {
                $y = $p1;
                $mon = $p2;
                $d = $p3;
                if (checkdate($mon, $d, $y)) {
                    return sprintf('%04d-%02d-%02d', $y, $mon, $d);
                }
            } else {
                // Format: DD-MM-YYYY (Indonesian default)
                $d = $p1;
                $mon = $p2;
                $y = $p3;
                if ($y < 100) $y += 2000;

                if (checkdate($mon, $d, $y)) {
                    return sprintf('%04d-%02d-%02d', $y, $mon, $d);
                }

                // Fallback for MM-DD-YYYY if DD > 12 and MM <= 12
                if (checkdate($d, $mon, $y)) {
                    return sprintf('%04d-%02d-%02d', $y, $d, $mon);
                }
            }
        }

        return null;
    }
}
