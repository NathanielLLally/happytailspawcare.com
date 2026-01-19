"use strict";
//find a way?
//<script src="https://unpkg.com/mathjs/lib/browser/math.js"></script>

function include(filename) {
  return HtmlService.createHtmlOutputFromFile(filename).getContent();
}


function testMath() {
  let math = include('math')
  //console.log(math)
  console.log(math.evaluate('2+2'))
}


const MIN_TIME_UNIT = 1;
const MAX_TIME_UNIT = 240;

const MODEL_TUNING = {
  BASELINE: "Conservative",
  LOWERQUARTILE: "Low-Key Flex",
  MEDIAN: "Reliable Prediction",
  UPPERQUARTILE: "Baller Bracket",
  MAX: "Boss Level"
}
const TIME_UNIT = {
  DAY: 0.35,
  WEEK: 0.25,
  MONTH: 1,
  QUARTER: 3,
  YEAR: 12
}

let gDataTable;

function today() {
  const today = new Date();
  var now = new Intl.DateTimeFormat('en-US').format(today);
  return now;
}

function assertValidModel(value) {
  if (!Object.values(MODEL_TUNING).includes(value)) {
    throw new Error(`Invalid Model: ${value}`);
  }
}

function assertValidMonthInRange(month) {
  if (!(typeof month === 'number' || month >= 0 || month <= MAX_TIME_UNIT )) {
    throw new Error(`month not valid or out of bounds: ${month}`);
  }
}

function assertValidTime(value) {
  if (!Object.values(TIME_UNIT).includes(value)) {
    throw new Error(`Invalid Time: ${value}`);
  }
}

const longTime = (t) => {
  let lt = "inf";
  switch (t) {
    case TIME_UNIT.DAY:
      lt = 'day';
      break;
    case TIME_UNIT.WEEK:
      lt = 'week';
      break;
    case TIME_UNIT.MONTH:
      lt = 'month';
      break;
    case TIME_UNIT.QUARTER:
      lt = 'quarter';
      break;
    case TIME_UNIT.YEAR:
      lt = 'year';
      break;
    default:
  }
  return lt;
}

//  return formatted date given TIME_UNIT enum with n, n being between 1 and MAX_TIME_UNIT;
//    \  /  \  /
//     \/    \/
//
const timeFunc = (t=TIME_UNIT.MONTH,n=MIN_TIME_UNIT) => {
  let lt = ""
  let sToday = `"${today()}"`;
  let fxYear=`year(${sToday})`;
  const fxFirstOfMonth = (month) => { 
    assertValidMonthInRange(month);
    return `DATE( ${fxYear},${month},1)`; 
  }

  switch (t) {
      //   today()
      //  1/01/2026 + 0.35 * 1
    case TIME_UNIT.DAY:
      //   "1/3/2026"
      //   lt = '=text(day(today()),"dddd")';
      //   txt=`=text(day(DATE( year(today()),1,${month}),"dddd")`
      lt =`=text(WEEKDAY(${sToday}, 1),"dddd")`;
      break;
    case TIME_UNIT.WEEK:
      //  week of, begins sunday
      //lt = '=weeknum(today())';
      lt =`=${fxFirstOfMonth(n)}-(WEEKDAY(${fxFirstOfMonth(n)})  - 1)`
      break;
    case TIME_UNIT.MONTH:
      // =text(C5,"mmm")
      lt = `=text(${fxFirstOfMonth(n)},"mmm")`;
      break;
    case TIME_UNIT.QUARTER:
      //
      lt = `=concat("Q",mod(${fxFirstOfMonth(n)},4))'`;
      break;
    case TIME_UNIT.YEAR:
      //
      //lt = '=year(today())';
      lt = `=text(${sToday}, "yy")`;
      break;
    default:
  }
  return lt;
}

const longModel = (m) => {
  let lm = "";
  switch (m) {
    case MODEL_TUNING.BASELINE:
      lm = "baseline";
      break;
    case MODEL_TUNING.LOWERQUARTILE:
      lm = "lowerquartile";
      break;
    case MODEL_TUNING.MEDIAN:
      lm = "median";
      break;

    case MODEL_TUNING.UPPERQUARTILE:
      lm = "upperquartile";
      break;

    case MODEL_TUNING.MAX:
      lm = "max";
      break;
    default:
  }
  return lm;
}

//
//
function calc(time, model, key, value) {

}

String.prototype.format = String.prototype.f = function() {
    var s = this,
        i = arguments.length;

    while (i--) {
        s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
    }
    return s;
};

//  takes timeUnits, modelTuning, data, n (time step index) 
//    returns resultSet
//
//  TODO rename this
//
function rCalc(t = TIME_UNIT.MONTH, m, timeStep = 0, tRange, fields = []) {
  assertValidTime(t);
  assertValidModel(m);

  //  console.log(`rCalc key: ${data[key]}`);

  //assert N
  /*
  data['CAC'] = data['cost_per_sale'];
  data['ARPA'] = data["revenue_per_client"];
  */

  //  derived or calculated fields
  //    sooooo algebra
  /*
      "time": TIME_UNIT.MONTH,
    "model": MODEL_TUNING.BASELINE,
    "graph": {"fields": [] },
    "avg_client_lifespan": "12",  //time base month
    "client_retention_rate": 0.8,
  //split these out per niche
    "revenue_per_client": 300,
    "overhead": 100,
    "profit_margin": 0.25,
    "cost_per_sale": 100, 
    */

function getRandomInt(max,min) {
  return Math.floor(Math.random() * (max-min+1)) + +min;
}

let funcFromStr = {
  //
  //BE SURE TO ADDRESS THE FULL KEY NAME WHEN ACCESSING GLOBAL DATA TABLE
  //  THERE IS NO SHORT FIELD NAME, THEY ALL CONTAING time_unit and model_tuning
  //
  //TODO vary data or have some tabulation input from actual leads sheet
  //  onboarding close rate transparency
  //
  'new_clients': (d,timeUnit,modelTuning, n) => {
    //  previous timeStep(n)
    //  if n == 0, initial is 'active_clients'
    d['close_rate']  = 0.05;
    
    //let fX = Math.round(+(getRandomInt(5,-5) + +d['new_leads'] ) * +d['close_rate']);
    let fX = Math.round(+(getRandomInt(5,-5) + +d['new_leads'] ));
    return fX;
  },


  //  for illustration purposes, it might be nice to have 
  //  the first time period containing initial values, deriving values for subsequent time periods
  //     either with n=0 or etc..?
  //
  //     currently, this function derives on time=[initial]
  //
  'active_clients': (d,timeUnit,modelTuning, n) => {
    //  
    //  initial is in fact 1
    let f0 = 'active_clients_{0}_{1}_{2}'.f(longTime(timeUnit),longModel(modelTuning), 0);

    let fp = 'active_clients_{0}_{1}_{2}'.f(longTime(timeUnit),longModel(modelTuning), (+n - 1));
    let f = 'new_clients_{0}_{1}_{2}'.f(longTime(timeUnit),longModel(modelTuning), (+n));

    console.log('f(x) active_clients f {0} d {1}'.f(f,d[f]))
    console.log('f(x) active_clients f prev {0} d prev {1}'.f(fp,d[fp]))

    //  if n == 1, initial,... [0] is set to d['active_clients'
    if (typeof(d[fp]) === 'undefined') {
      d[f0] = +d['active_clients']
      console.log('fp === undef, initial from form submission: {0}'.f(d['active_clients']));
    }

    let fX = Math.round(+d[fp] * +d['client_retention_rate']) + +d[f] 
    let a = d[fp]
    let b = d['client_retention_rate'] 
    let c = +a * +b

    console.log('f(x) active_clients n {0} f {1} df {2} fp {3} dfp {4} dfp * crr {5} c {6} fx {7}'.f(n, f, 
      +d[f], fp, d[fp], +d[fp] * +d['client_retention_rate'], c, fX));
    return fX;
  },
  'non_retained_clients': (d,timeUnit,modelTuning,n) => {
    let f = 'active_clients_{0}_{1}_{2}'.f(longTime(timeUnit),longModel(modelTuning), (+n));
    let fX;
    switch (modelTuning) {
      case MODEL_TUNING.BASELINE:
        fX = Math.round(-(+d[f] * (1 - +d['client_retention_rate'])));
        break;
      default:
        fX = Math.round(-(+d[f] * (1 - +d['client_retention_rate'])));
    }
    console.log('f(x) active_clients: {0} non_retained_clients {1}'.f(d[f],fX));
    return fX;
  },
  'lifetime_value': (d,timeUnit,modelTuning, n) => {
    console.log('f(x) lifetime_value');
    switch (modelTuning) {
      case MODEL_TUNING.BASELINE:
        return +d['revenue_per_client'] * +d['avg_client_lifespan'];
        break;
      case MODEL_TUNING.MAX:
        return +d['revenue_per_client'] * +d['avg_client_lifespan'];
        break;
      default:
        return +d['revenue_per_client'] * +d['avg_client_lifespan'] ;
    }
  },
  //Total money earned from sales before any costs.
  'gross_revenue': (d,timeUnit,modelTuning, n) => {
    console.log('f(x) gross_revenue');
    return (+d['revenue_per_client'] * +d['active_clients'])
  },
  'net_profit_per_client': (d,timeUnit,modelTuning, n) => {
    console.log('f(x) net_profit_per_client');
    return (+d['gross_revenue'] * +d['profit_margin'])
  },
  //TODO: rangeSlider
  //
  'ad_spend': (d,timeUnit,modelTuning, n) => {

    /*d['time_begin']
    d['time_end']
    */
    let range = (+d['ad_spend_max'] - +d['ad_spend_min']) / tRange
    let fX
    switch (modelTuning) {
      case MODEL_TUNING.BASELINE:
        fX = Math.round(+d['ad_spend_min'] + range * (n-1))
        break;
      case MODEL_TUNING.MAX:
        fX =  Math.round(+d['ad_spend_min'] + (+d['ad_spend_min'] / +n))
        break;
      default:
        fX =  Math.round(+d['ad_spend_min'] + (+d['ad_spend_min'] * (n / MAX_TIME_UNIT)))
    }
    console.log(`f(x) t ${timeUnit} m ${modelTuning} n ${n} tRange ${tRange} range ${range} ad_spend max ${d['ad_spend_max']} min ${d['ad_spend_min']} fX ${fX}` );
    return fX;
  },
  'new_leads': (d,timeUnit,modelTuning, n) => {
    let fX = +d['new_leads'];
    console.log(`f(x) new_leads ${fX}`);
    return fX;
  },
  'client_retention_rate': (d,timeUnit,modelTuning, n) => {
    let fX = +d['client_retention_rate'];
    console.log(`f(x) client_retention_rate ${fX}`);
    return fX;
  },
  //https://developers.google.com/chart/interactive/docs/customizing_tooltip_content#placing-charts-in-tooltips
  //https://developers.google.com/chart/interactive/docs//roles#what-roles-are-available
     'tooltip': (d,timeUnit,modelTuning, n) => {
       return 'Total money earned from sales before any costs.';
     },
};

//TODO
//  
//console.log(`TODO assert that ('month' == 'month') is true [${longTime(t)}]`);

//  field renaming can happen here
//
let out = [timeFunc(t,timeStep)]
//
for (let [field, fn] of Object.entries(funcFromStr)) {
  let f = field + "_" + longTime(t) + "_" + longModel(m) + "_" + (timeStep);

  //  derivedFieldName_stringOfTimeUnit_stringOfModelTuning = value
  let v = fn(gDataTable, t, m, timeStep);
  
  console.log('rCalc: funcFromStr n:{0} f:{1} v:{2}'.f(timeStep,f,v)); 
  gDataTable[f] = v;

  if (fields.includes(field)) {
    out.push(v);
  } else {
    console.log(`field not included ${field}`)
  }
}
return out;
}


//  data constructor from user's form input
//
function funroll(nicheEnum, formLink) {

  //Client Perspective ROI	
  //Leads	50
  //Lead sell price to client	$55.00
  //Client Close %	5.00%
  //Could work in Contact %	60.00%
  //Leads contacted	30.00
  //these numbers are typical cases	
  //closed as paying clients (% of contacted)	20%
  //client close %	12.00%
  //client closed	10.00
  //showed genuine interest	50.00%
  //booked a consult	40.00%
  //Closed Deal Value Example	$1,000.00
  //Gross Income	$10,000.00
  //leads cost	$2,750.00
  //Net return	$7,250.00
  //Cost per Sale	$458.33
  //gross ROI	$2.64
  //Profit Margin (for Net Rev in ROAS)	25.00%
  //Net profit for leads	$2,500.00
  //Net ROAS	$0.91

  // Legend:
  //Revenue: Total sales volume, tracking market presence.
  //Gross Profit: How efficiently you produce and price your core product.
  //Net Profit: How much money is truly left after all costs, indicating overall financial health. 
  //ARPA average revenue per account
  //LTV (Lifetime Value)	Total revenue expected from a client over their lifetime	LTV = ARPA × AvgClientRetentionMonths
  //CAC (Customer Acquisition Cost)	Average cost to acquire a new client	CAC = TotalMarketingSpend / NewClientsAcquired
  //LTV:CAC ratio	Measures profitability per acquisition	LTV:CAC = LTV ÷ CAC

  var data = {
    "form_row_link": formLink,
    "time": TIME_UNIT.MONTH,
    "model": MODEL_TUNING.BASELINE,
    "graph": {"fields": [] },
    "avg_client_lifespan": "12",  //time base month
    "client_retention_rate": 0.8,
    "new_leads": 10,
    //split these out per niche
    "revenue_per_client": 300,
    "overhead": 100,
    "profit_margin": 0.25,
    "cost_per_sale": 100, //CAC (Customer Acquisition Cost)	= TotalMarketingSpend / NewClientsAcquired
  };
  data["time_begin"] = 0;

  //TODO: fixthis
  //data["time_end"] = data["time"] * MAX_TIME_UNIT;
  data["time_end"] = 11;


  switch (nicheEnum) {
    case "emergency":
      data["ad_spend_min"] = "20000";
      data["ad_spend_max"] = "100000";
      data['service'] = ['Emergency Triage & Critical Care','After-Hours Diagnostics','Trauma & Surgery Intervention'];
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data['service_price'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data['service_cost'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];

      break;
    case "surgical":
      data['service'] = ['Orthopedic Surgery Package','Oncology Treatment Protocol','Specialty Diagnostics'];
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "10000";
      data["ad_spend_max"] = "50000";
      break;
    case "franchise_vet":
      data['service'] = ['Annual Wellness Plan','Pet Vaccination Package','Preventative Dental Program'];
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "8000";
      data["ad_spend_max"] = "40000";
      break;
    case "training":
      data['service'] = ['Board & Train Intensive Program','Obedience Group Classes','One-on-One Behavioral Coaching']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "5000";
      data["ad_spend_max"] = "25000";
      break;
    case "boarding":
      data['service'] = ['Overnight Boarding Package','Daycare Enrollment','Playtime & Socialization Add-on']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "4000";
      data["ad_spend_max"] = "20000";
      break;
    case "mobile_vet":
      data['service'] = ['Mobile Wellness Exam','At-Home Vaccination Service','In-Home Lab & Diagnostics']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "3000";
      data["ad_spend_max"] = "15000";
      break;
    case "luxury_sitting":
      data['service'] = ['Premium Overnight Sitting','Daily Check-Ins & Playtime','Special Needs / Med Admin']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "3000";
      data["ad_spend_max"] = "12000";
      break;
    case "luxury_grooming":
      data['service'] = ['Full Groom & Styling','Show Groom Prep','Specialty Breed Maintenance']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "2000";
      data["ad_spend_max"] = "10000";
      break;
    case "walking":
      data['service'] = ['30-min Daily Walk Plan','60-min Premium Walk Plan','Pack Walk / Socialization Class']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "1500";
      data["ad_spend_max"] = "8000";
      break;
    case "transportation":
      data['service'] = ['Airport Pet Transfer','Vet / Grooming Pickup & Drop-Off','Long-Distance Relocation']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "1500";
      data["ad_spend_max"] = "7000";
      break;
    case "cremation":
      data['service'] = ['Individual Cremation','Communal Cremation','Memorial Keepsake Package']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "1000";
      data["ad_spend_max"] = "6000";
      break;
    case "delivery":
      data['service'] = ['Monthly Raw Food Delivery','Prescription Diet Subscription','Supplemental Treat Packs']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "1000";
      data["ad_spend_max"] = "5000";
      break;
    case "photo":
      data['service'] = ['Studio Portrait Session','Outdoor Adventure Shoot','Event Coverage (Birthday / Adoption Day)']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "500";
      data["ad_spend_max"] = "3000";
      break;
    case "exotic":
      data['service'] = ['Reptile Health Exam','Bird Beak / Feather Care','Small Mammal Wellness Plan']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "300";
      data["ad_spend_max"] = "2000";
      break;
    case "waste":
      data['service'] = ['Weekly Pooper Scooper Plan','Bi-Weekly Clean-Up','One-Time Yard Clean Event']
      data['service_hours'] = [{'min': 2, 'median': 3, 'max': 4}, {'min':0, "median":0, "max":0}, {'min':0,'median':0,'max':0}];
      data["ad_spend_min"] = "300";
      data["ad_spend_max"] = "1500";
      break;
    default:
  }
  return data;
}

function makeGraph(d) {
  console.log("makeGraph() begin")
}

function updateSheetData() {
  // Get the active spreadsheet
  let sheet_url = "";
  const ss = SpreadsheetApp.openByUrl(sheet_url);

  // Get the sheet you want to update by name
  // Replace 'Sheet1' with the actual name of your sheet
  var sheet = ss.getSheetByName('Sheet1');

  if (sheet) {
    // Define the range you want to update (e.g., cell A1)
    var range = sheet.getRange('A1');

    // Set the new value for the range
    range.setValue('Hello, World!');

    // You can also set a range of multiple cells using a 2D array
    // Example: Update range A2:B3 with new data
    var multiCellRange = sheet.getRange('A2:B3');
    var newData = [['Data 1', 'Data 2'], ['Data 3', 'Data 4']];
    multiCellRange.setValues(newData);
    // Force all pending changes to be applied to the sheet
    SpreadsheetApp.flush();
  } else {
    Logger.log('Sheet not found. Check the sheet name.');
  }
}


function populateSheet() {
  console.log("populateSheet() begin")
  //'https://script.google.com/macros/s/AKfycbw4zHkAUbRLoSHmkYuleuOAUr6o9hMzl7ule6A6IEnEytsxfMUCQG3tFNcSqVfGHUwr/exec'
  //https://script.google.com/macros/s/AKfycbw4zHkAUbRLoSHmkYuleuOAUr6o9hMzl7ule6A6IEnEytsxfMUCQG3tFNcSqVfGHUwr/exec
  var path = "macros/s/AKfycbw4zHkAUbRLoSHmkYuleuOAUr6o9hMzl7ule6A6IEnEytsxfMUCQG3tFNcSqVfGHUwr/exec";
  var query = "?action=read"
  var url = "https://script.google.com/"+path+query; // Replace with your URL
  var html;

  console.log("await fetchXHR "+url)
  html = " fetching data <p>";
  let sheet_url = 'https://docs.google.com/spreadsheets/d/1YYVl5EGOy2WOk74l3DmtaugP5RgW_c6UQDtJkTE4itE/edit?resourcekey=&gid=25088639#gid=25088639';
  const ss = SpreadsheetApp.openByUrl(sheet_url);
  const form_sheet = ss.getSheetByName('Forminator');

  //console.log(`spreadsheet name ${form_sheet.getFormUrl()}`)

  //ui.showModalDialog(HtmlService.createHtmlOutput(html), 'Executing sequence');
  let response = UrlFetchApp.fetch(url);
  //  data = response.getContentText();
  var data = JSON.parse(response.getContentText());

  var n = data.length + 1;//data.slice(-1);
  let el = data.pop();
  //  var niche = el["Niche|select-1"];
  var active_clients = el["Active Clients|number-1"];

  var nicheArray = JSON.parse(el["Niche|select-1"]);
  let niche = nicheArray.pop();

  console.log(`niche ${niche} active_clients: ${active_clients}`);
  let link = `${sheet_url}&${n}:${n}`;
  gDataTable = funroll(niche, `=HYPERLINK("${link}", "row ${n}")`);

  gDataTable = addDataSubNiche(gDataTable);
  console.log(gDataTable)
  //  let graphTable = funroll(niche, `=HYPERLINK("${link}", "row ${n}")`);

  gDataTable['active_clients'] = active_clients;

  assertValidTime(gDataTable.time)
  assertValidModel(gDataTable.model)

  //  TODO: this could & should be tied to the UI
  //
  const data_sheet = ss.getSheetByName('DataTable');
  const graph_sheet = ss.getSheetByName('Graph');

  let c = 1;  let r = 1;
  for (let [k, v] of Object.entries(gDataTable)) {
    console.log("k: "+k+" v:"+v);
    data_sheet.getRange(r,1).setValue(k);  
    data_sheet.getRange(r,2).setValue(v);
    r+=1;
  }

  //let graphFields = ["month", "lifetime_value"];
  //  getA1Notation()
  //console.log(graph_sheet.getRange(1,1));
  //for (let f of graphFields) {
  //  graphTable["A1"]= longTime(gDataTable.time);
  //}

  //   console.log(JSON.stringify(graphTable));

  // You can also set a range of multiple cells using a 2D array
  // Example: Update range A2:B3 with new data
  //    var multiCellRange = sheet.getRange('A2:B3');
  //    var newData = [['Data 1', 'Data 2'], ['Data 3', 'Data 4']];
  //    multiCellRange.setValues(newData);
  // Force all pending changes to be applied to the sheet
  //    SpreadsheetApp.flush();

  //    var graphTable = gDataTable
  var newData = [];

  var fields = ["new_clients", "active_clients", "non_retained_clients", "gross_revenue", "ad_spend","new_leads","client_retention_rate"];
  var header = ["New Clients", "Active Clients", "Non-retained Clients", "Revenue", "Ad Spend","New Leads","Client Retention Rate"];
  //  headers
  //
  let headers = [longTime(gDataTable.time)];
  for (let el of header) {
    headers.push(el);
  }
  newData.push(headers);
  console.log(JSON.stringify(newData));

  let tRange = +gDataTable['time_end'] - +gDataTable['time_begin']

  // from 1 
  //
  for (let timeStep = gDataTable['time_begin'] + 1; timeStep <= gDataTable['time_end']; timeStep += 1) {

    newData.push( rCalc(gDataTable.time, gDataTable.model, timeStep,tRange,fields) );
  }

  console.log('pushing data to graph sheet');
  console.log(JSON.stringify(newData));

  //    var multiCellRange = graph_sheet.getRange('A1:N4');
  //  const range = sheet.getRange(startRow, startColumn, numRows, numColumns);
  const numRows = newData.length;
  const numColumns = newData[0] ? newData[0].length : 0;

  var multiCellRange = graph_sheet.getRange(1,1,numRows, numColumns);
  multiCellRange.setValues(newData);
  // Force all pending changes to be applied to the sheet
  SpreadsheetApp.flush();

  //TODO: FILTER FIELDS
  /*
  c = 1;  r = 1;
    for (let row of // Object.entries(graphTable)) {

      graph_sheet.getRange(1,c).setValue(field);
    }
    for (let row of // Object.entries(graphTable)) {
      graph_sheet.getRange(2,c).setValue(value);
      r=r+1;
    }
    */
}

function doGet(e) {
  if (e.parameter.action === 'read') {
    return readData();
  }

  if (e.parameter.action === 'update') {
    return populateSheet();
  }

  if (e.parameter.action === 'populate') {
    return populateSheet();
  }

  return ContentService
    .createTextOutput('Invalid action')
    .setMimeType(ContentService.MimeType.TEXT);
}

/**
 * WRITE DATA (Web App POST)
 * Body JSON: { "id": 1, "status": "Active" }
 */
function doPost(e) {
  const data = JSON.parse(e.postData.contents);
  return writeData(data);
}

/**
 * Read all sheet data as JSON
 */
function readData() {

  const ss = SpreadsheetApp.openByUrl(
    'https://docs.google.com/spreadsheets/d/1YYVl5EGOy2WOk74l3DmtaugP5RgW_c6UQDtJkTE4itE/edit?resourcekey=&gid=25088639#gid=25088639'
  );
  const sheet = ss.getSheetByName('Forminator');

  const values = sheet.getDataRange().getValues();
  const headers = values.shift();

  const rows = values.map(row => {
    return headers.reduce((obj, key, i) => {
      obj[key] = row[i];
      return obj;
    }, {});
  });

  return ContentService
    .createTextOutput(JSON.stringify(rows))
    .setMimeType(ContentService.MimeType.JSON);
}

/**
 * Update row by ID
 */
function writeData(sheet, data) {
  console.log("writeData ")
  const values = sheet.getDataRange().getValues();
  console.log(values)
  const headers = values[0];

  console.log(data)

  for (let row = 1; row < values.length; row++) {
    for (let col = 1; col< values[row].length; col++) {
      sheet.getRange(row, col).setValue("suckit");
    }
  }
  return ContentService.createTextOutput('Updated');

  //return ContentService.createTextOutput('ID not found');
}



