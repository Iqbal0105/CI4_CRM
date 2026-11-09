const path = require('path');
let tesseractPath = path.resolve(__dirname, '../../../writable/node_modules/tesseract.js');
if (!require('fs').existsSync(tesseractPath)) {
    tesseractPath = 'C:/Users/Lenovo/.gemini/antigravity/brain/500a4c5b-1c38-400d-b666-8a79ae0928bc/scratch/node_modules/tesseract.js';
}
const T = require(tesseractPath);
const fs = require('fs');

async function ocrDir(dirPath) {
    const files = fs.readdirSync(dirPath)
        .filter(f => /^page_\d+\.png$/i.test(f))
        .sort((a, b) => {
            const numA = parseInt(a.replace(/[^0-9]/g, ''));
            const numB = parseInt(b.replace(/[^0-9]/g, ''));
            return numA - numB;
        });

    if (files.length === 0) {
        console.log(JSON.stringify({}));
        return;
    }

    const worker = await T.createWorker('eng');
    const results = {};

    for (const f of files) {
        const fullPath = path.join(dirPath, f);
        try {
            const res = await worker.recognize(fullPath);
            results[f] = res.data && res.data.text ? res.data.text : '';
        } catch (e) {
            results[f] = '';
        }
    }

    await worker.terminate();
    // Write output to results.json in dirPath to avoid stdout buffer size limits
    const outJsonPath = path.join(dirPath, 'ocr_results.json');
    fs.writeFileSync(outJsonPath, JSON.stringify(results), 'utf8');
    console.log('SUCCESS');
}

const targetDir = process.argv[2];
if (targetDir) {
    ocrDir(targetDir).catch(err => {
        console.error(err);
        process.exit(1);
    });
}

