function doGet() {
  return HtmlService.createHtmlOutputFromFile('index')
    .setTitle('Interactive Time Chart')
    .setXFrameOptionsMode(HtmlService.XFrameOptionsMode.ALLOWALL);
}

function getData() {
  // Generate sample time-series data
  const data = [];
  const startDate = new Date(2024, 0, 1); // January 1, 2024
  
  for (let i = 0; i < 365; i++) {
    const date = new Date(startDate);
    date.setDate(date.getDate() + i);
    
    // Generate sample values with some variation
    const value1 = 50 + Math.sin(i / 30) * 20 + Math.random() * 10;
    const value2 = 40 + Math.cos(i / 20) * 15 + Math.random() * 8;
    
    data.push([date, value1, value2]);
  }
  
  return data;
}