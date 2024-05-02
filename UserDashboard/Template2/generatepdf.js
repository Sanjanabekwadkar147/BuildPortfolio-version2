const puppeteer = require('puppeteer');

async function generatePDF() {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  
  // Adjust viewport size if necessary
  await page.setViewport({ width: 1200, height: 800 });
  
  // Navigate to your webpage
  await page.goto('index.php');

  // Generate PDF
  await page.pdf({ path: 'portfolio.pdf', format: 'A4' });

  await browser.close();
}

generatePDF();
