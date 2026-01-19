
function addDataSubNiche(data={}) {
  //if (hasOwnProperty !Object.data.includes("niche")) {
   // data["niche"] = "emergency"
  if (!"niche" in data) {
     throw new Error(`passed invalid dataTable, missing 'niche'`);
  }
  let nicheEnum = data["niche"]
    d = JSON.parse('{"emergency":{"short_name":"emergency", "sub_niche": "Emergency / 24-7 Veterinary Clinics", "profit_margin_min": 5,"profit_margin_max": 12},     "surgical":{ "short_name":"surgical", "sub_niche": "Specialty & Surgical Veterinary Practices", "profit_margin_min": 15, "profit_margin_max": 30 }, "franchise_vet":{ "short_name":"franchise_vet", "sub_niche": "Franchise / Multi-Location Vet Clinics", "profit_margin_min": 10, "profit_margin_max": 20 }, "training":{ "short_name":"training", "sub_niche": "Dog Training (Behavioral / Aggression / Board & Train)", "profit_margin_min": 25, "profit_margin_max": 45 }, "boarding":{ "short_name":"boarding", "sub_niche": "Pet Boarding & Daycare (Urban / High-Capacity)", "profit_margin_min": 15, "profit_margin_max": 30 }, "mobile_vet":{ "short_name":"mobile_vet", "sub_niche": "Mobile Veterinary Services", "profit_margin_min": 20, "profit_margin_max": 35 }, "luxury_sitting":{ "short_name":"luxury_sitting", "sub_niche": "Luxury Pet Sitting (In-Home Overnight)", "profit_margin_min": 25, "profit_margin_max": 45 }, "luxury_grooming":{ "short_name":"luxury_grooming", "sub_niche": "Luxury Pet Sitting (In-Home Overnight)", "profit_margin_min": 25, "profit_margin_max":45 }, "walking":{ "short_name":"walking", "sub_niche": "Dog Walking (Urban Recurring Packages)", "profit_margin_min": 30, "profit_margin_max": 55 }, "transportation":{ "short_name":"transportation", "sub_niche":"Pet Transportation / Pet Taxi / Airline-ready transport", "profit_margin_min":20, "profit_margin_max":40 }, "cremation":{ "short_name":"cremation", "sub_niche": "Pet Cremation & Memorial Services", "profit_margin_min": 35, "profit_margin_max": 60 }, "delivery":{ "short_name":"delivery", "sub_niche": "Pet Transportation / Pet Taxi / Airline-ready transport", "profit_margin_min": 20, "profit_margin_max": 40 },"photo":{ "short_name":"photo", "sub_niche": "Pet Photography / Events", "profit_margin_min": 30, "profit_margin_max": 60 }, "exotic":{ "short_name":"exotic", "sub_niche": "Exotic Pet Care (Reptiles Birds Small Mammals)", "profit_margin_min": 15, "profit_margin_max": 35 }, "waste":{ "short_name":"waste", "sub_niche": "Pet Waste Removal (Pooper Scooper)", "profit_margin_min": 40, "profit_margin_max": 70 }}');
  Object.assign(data, d[nicheEnum]);
  console.log(data)
    return data;
}
