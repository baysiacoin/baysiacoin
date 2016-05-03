(function() {
  if (typeof window === "undefined") {
    return;
  }

  window.Authy = {};

  if (document.getElementsByClassName == null) {
    document.getElementsByClassName = function(className, parentElement) {
      var child, children, elements, i, length;
      children = (parentElement || document.body).getElementsByTagName("*");
      elements = [];
      child = void 0;
      i = 0;
      length = children.length;
      while (i < length) {
        child = children[i];
        if ((" " + child.className + " ").indexOf(" " + className + " ") !== -1) {
          elements.push(child);
        }
        i++;
      }
      return elements;
    };
    HTMLDivElement.prototype.getElementsByClassName = function(className) {
      return document.getElementsByClassName(className, this);
    };
  }

  window.Authy.UI = function() {
    var absolutePosFor, buildItem, countriesList, disableAutocompleteAuthyToken, findAndSetupCountries, getKeyCode, hideAutocompleteList, processKey13, processKey38, processKey40, self, setActive, setCountryField, setupAuthyTokenValidation, setupCellphoneValidation, setupCountriesDropdown, setupCountriesDropdownPosition, setupEvents, setupTooltip, setupTooltipPosition, tooltipMessage, tooltipTitle;
    self = this;
    tooltipTitle = "Authy Help Tooltip";
    tooltipMessage = "This is a help tooltip for your users. You can set the message by doing: authyUI.setTooltip(\"title\", \"message\");";
    countriesList = [
      {
        "country": "United States of America (+1)",
        "code": "1",
        "shortname": "US"
      }, {
        "country": "Canada (+1)",
        "code": "1",
        "shortname": "CA"
      }, {
        "country": "Russia (+7)",
        "code": "7",
        "shortname": "RU"
      }, {
        "country": "Kazakhstan (+7)",
        "code": "7",
        "shortname": "KZ"
      }, {
        "country": "Egypt (+20)",
        "code": "20",
        "shortname": "EG"
      }, {
        "country": "South Africa (+27)",
        "code": "27",
        "shortname": "ZA"
      }, {
        "country": "Greece (+30)",
        "code": "30",
        "shortname": "GR"
      }, {
        "country": "Netherlands (+31)",
        "code": "31",
        "shortname": "NL"
      }, {
        "country": "Belgium (+32)",
        "code": "32",
        "shortname": "BE"
      }, {
        "country": "France (+33)",
        "code": "33",
        "shortname": "FR"
      }, {
        "country": "Spain (+34)",
        "code": "34",
        "shortname": "ES"
      }, {
        "country": "Hungary (+36)",
        "code": "36",
        "shortname": "HU"
      }, {
        "country": "Italy (+39)",
        "code": "39",
        "shortname": "IT"
      }, {
        "country": "Romania (+40)",
        "code": "40",
        "shortname": "RO"
      }, {
        "country": "Switzerland (+41)",
        "code": "41",
        "shortname": "CH"
      }, {
        "country": "Austria (+43)",
        "code": "43",
        "shortname": "AT"
      }, {
        "country": "United Kingdom (+44)",
        "code": "44",
        "shortname": "GB"
      }, {
        "country": "Guernsey (+44)",
        "code": "44",
        "shortname": "GG"
      }, {
        "country": "Isle of Man (+44)",
        "code": "44",
        "shortname": "IM"
      }, {
        "country": "Jersey (+44)",
        "code": "44",
        "shortname": "JE"
      }, {
        "country": "Denmark (+45)",
        "code": "45",
        "shortname": "DK"
      }, {
        "country": "Sweden (+46)",
        "code": "46",
        "shortname": "SE"
      }, {
        "country": "Norway (+47)",
        "code": "47",
        "shortname": "NO"
      }, {
        "country": "Poland (+48)",
        "code": "48",
        "shortname": "PL"
      }, {
        "country": "Germany (+49)",
        "code": "49",
        "shortname": "DE"
      }, {
        "country": "Peru (+51)",
        "code": "51",
        "shortname": "PE"
      }, {
        "country": "Mexico (+52)",
        "code": "52",
        "shortname": "MX"
      }, {
        "country": "Cuba (+53)",
        "code": "53",
        "shortname": "CU"
      }, {
        "country": "Argentina (+54)",
        "code": "54",
        "shortname": "AR"
      }, {
        "country": "Brazil (+55)",
        "code": "55",
        "shortname": "BR"
      }, {
        "country": "Chile (+56)",
        "code": "56",
        "shortname": "CL"
      }, {
        "country": "Colombia (+57)",
        "code": "57",
        "shortname": "CO"
      }, {
        "country": "Venezuela (+58)",
        "code": "58",
        "shortname": "VE"
      }, {
        "country": "Malaysia (+60)",
        "code": "60",
        "shortname": "MY"
      }, {
        "country": "Australia (+61)",
        "code": "61",
        "shortname": "AU"
      }, {
        "country": "Indonesia (+62)",
        "code": "62",
        "shortname": "ID"
      }, {
        "country": "Philippines (+63)",
        "code": "63",
        "shortname": "PH"
      }, {
        "country": "New Zealand (+64)",
        "code": "64",
        "shortname": "NZ"
      }, {
        "country": "Singapore (+65)",
        "code": "65",
        "shortname": "SG"
      }, {
        "country": "Thailand (+66)",
        "code": "66",
        "shortname": "TH"
      }, {
        "country": "Japan (+81)",
        "code": "81",
        "shortname": "JP"
      }, {
        "country": "Korea (+South) (+82)",
        "code": "82",
        "shortname": "KR"
      }, {
        "country": "Vietnam (+84)",
        "code": "84",
        "shortname": "VN"
      }, {
        "country": "China (+86)",
        "code": "86",
        "shortname": "CN"
      }, {
        "country": "Turkey (+90)",
        "code": "90",
        "shortname": "TR"
      }, {
        "country": "India (+91)",
        "code": "91",
        "shortname": "IN"
      }, {
        "country": "Pakistan (+92)",
        "code": "92",
        "shortname": "PK"
      }, {
        "country": "Afghanistan (+93)",
        "code": "93",
        "shortname": "AF"
      }, {
        "country": "Sri Lanka (+94)",
        "code": "94",
        "shortname": "LK"
      }, {
        "country": "Myanmar (+95)",
        "code": "95",
        "shortname": "MM"
      }, {
        "country": "Iran (+98)",
        "code": "98",
        "shortname": "IR"
      }, {
        "country": "Morocco (+212)",
        "code": "212",
        "shortname": "MA"
      }, {
        "country": "Algeria (+213)",
        "code": "213",
        "shortname": "DZ"
      }, {
        "country": "Tunisia (+216)",
        "code": "216",
        "shortname": "TN"
      }, {
        "country": "Libya (+218)",
        "code": "218",
        "shortname": "LY"
      }, {
        "country": "Gambia (+220)",
        "code": "220",
        "shortname": "GM"
      }, {
        "country": "Senegal (+221)",
        "code": "221",
        "shortname": "SN"
      }, {
        "country": "Mauritania (+222)",
        "code": "222",
        "shortname": "MR"
      }, {
        "country": "Mali Republic (+223)",
        "code": "223",
        "shortname": "ML"
      }, {
        "country": "Guinea (+224)",
        "code": "224",
        "shortname": "GN"
      }, {
        "country": "Ivory Coast (+225)",
        "code": "225",
        "shortname": "CI"
      }, {
        "country": "Burkina Faso (+226)",
        "code": "226",
        "shortname": "BF"
      }, {
        "country": "Niger (+227)",
        "code": "227",
        "shortname": "NE"
      }, {
        "country": "Togo (+228)",
        "code": "228",
        "shortname": "TG"
      }, {
        "country": "Benin (+229)",
        "code": "229",
        "shortname": "BJ"
      }, {
        "country": "Mauritius (+230)",
        "code": "230",
        "shortname": "MU"
      }, {
        "country": "Liberia (+231)",
        "code": "231",
        "shortname": "LR"
      }, {
        "country": "Sierra Leone (+232)",
        "code": "232",
        "shortname": "SL"
      }, {
        "country": "Ghana (+233)",
        "code": "233",
        "shortname": "GH"
      }, {
        "country": "Nigeria (+234)",
        "code": "234",
        "shortname": "NG"
      }, {
        "country": "Chad (+235)",
        "code": "235",
        "shortname": "TD"
      }, {
        "country": "Central African Republic (+236)",
        "code": "236",
        "shortname": "CF"
      }, {
        "country": "Cameroon (+237)",
        "code": "237",
        "shortname": "CM"
      }, {
        "country": "Cape Verde Islands (+238)",
        "code": "238",
        "shortname": "CV"
      }, {
        "country": "Sao Tome and Principe (+239)",
        "code": "239",
        "shortname": "ST"
      }, {
        "country": "Gabon (+241)",
        "code": "241",
        "shortname": "GA"
      }, {
        "country": "Congo, Democratic Republ (+243)",
        "code": "243",
        "shortname": "CD"
      }, {
        "country": "Angola (+244)",
        "code": "244",
        "shortname": "AO"
      }, {
        "country": "Guinea-Bissau (+245)",
        "code": "245",
        "shortname": "GW"
      }, {
        "country": "Seychelles (+248)",
        "code": "248",
        "shortname": "SC"
      }, {
        "country": "Sudan (+249)",
        "code": "249",
        "shortname": "SD"
      }, {
        "country": "Rwanda (+250)",
        "code": "250",
        "shortname": "RW"
      }, {
        "country": "Ethiopia (+251)",
        "code": "251",
        "shortname": "ET"
      }, {
        "country": "Somalia (+252)",
        "code": "252",
        "shortname": "SO"
      }, {
        "country": "Djibouti (+253)",
        "code": "253",
        "shortname": "DJ"
      }, {
        "country": "Kenya (+254)",
        "code": "254",
        "shortname": "KE"
      }, {
        "country": "Tanzania (+255)",
        "code": "255",
        "shortname": "TZ"
      }, {
        "country": "Uganda (+256)",
        "code": "256",
        "shortname": "UG"
      }, {
        "country": "Burundi (+257)",
        "code": "257",
        "shortname": "BI"
      }, {
        "country": "Mozambique (+258)",
        "code": "258",
        "shortname": "MZ"
      }, {
        "country": "Zambia (+260)",
        "code": "260",
        "shortname": "ZM"
      }, {
        "country": "Madagascar (+261)",
        "code": "261",
        "shortname": "MG"
      }, {
        "country": "Reunion (+262)",
        "code": "262",
        "shortname": "RE"
      }, {
        "country": "Zimbabwe (+263)",
        "code": "263",
        "shortname": "ZW"
      }, {
        "country": "Namibia (+264)",
        "code": "264",
        "shortname": "NA"
      }, {
        "country": "Malawi (+265)",
        "code": "265",
        "shortname": "MW"
      }, {
        "country": "Lesotho (+266)",
        "code": "266",
        "shortname": "LS"
      }, {
        "country": "Botswana (+267)",
        "code": "267",
        "shortname": "BW"
      }, {
        "country": "Swaziland (+268)",
        "code": "268",
        "shortname": "SZ"
      }, {
        "country": "Mayotte Island (+269)",
        "code": "269",
        "shortname": "YT"
      }, {
        "country": "Aruba (+297)",
        "code": "297",
        "shortname": "AW"
      }, {
        "country": "Faroe Islands (+298)",
        "code": "298",
        "shortname": "FO"
      }, {
        "country": "Greenland (+299)",
        "code": "299",
        "shortname": "GL"
      }, {
        "country": "Gibraltar (+350)",
        "code": "350",
        "shortname": "GI"
      }, {
        "country": "Portugal (+351)",
        "code": "351",
        "shortname": "PT"
      }, {
        "country": "Luxembourg (+352)",
        "code": "352",
        "shortname": "LU"
      }, {
        "country": "Ireland (+353)",
        "code": "353",
        "shortname": "IE"
      }, {
        "country": "Iceland (+354)",
        "code": "354",
        "shortname": "IS"
      }, {
        "country": "Albania (+355)",
        "code": "355",
        "shortname": "AL"
      }, {
        "country": "Malta (+356)",
        "code": "356",
        "shortname": "MT"
      }, {
        "country": "Cyprus (+357)",
        "code": "357",
        "shortname": "CY"
      }, {
        "country": "Finland (+358)",
        "code": "358",
        "shortname": "FI"
      }, {
        "country": "Bulgaria (+359)",
        "code": "359",
        "shortname": "BG"
      }, {
        "country": "Lithuania (+370)",
        "code": "370",
        "shortname": "LT"
      }, {
        "country": "Latvia (+371)",
        "code": "371",
        "shortname": "LV"
      }, {
        "country": "Estonia (+372)",
        "code": "372",
        "shortname": "EE"
      }, {
        "country": "Moldova (+373)",
        "code": "373",
        "shortname": "MD"
      }, {
        "country": "Armenia (+374)",
        "code": "374",
        "shortname": "AM"
      }, {
        "country": "Belarus (+375)",
        "code": "375",
        "shortname": "BY"
      }, {
        "country": "Andorra (+376)",
        "code": "376",
        "shortname": "AD"
      }, {
        "country": "Monaco (+377)",
        "code": "377",
        "shortname": "MC"
      }, {
        "country": "San Marino (+378)",
        "code": "378",
        "shortname": "SM"
      }, {
        "country": "Ukraine (+380)",
        "code": "380",
        "shortname": "UA"
      }, {
        "country": "Serbia (+381)",
        "code": "381",
        "shortname": "RS"
      }, {
        "country": "Montenegro (+382)",
        "code": "382",
        "shortname": "ME"
      }, {
        "country": "Croatia (+385)",
        "code": "385",
        "shortname": "HR"
      }, {
        "country": "Slovenia (+386)",
        "code": "386",
        "shortname": "SI"
      }, {
        "country": "Bosnia-Herzegovina (+387)",
        "code": "387",
        "shortname": "BA"
      }, {
        "country": "Macedonia (+389)",
        "code": "389",
        "shortname": "MK"
      }, {
        "country": "Czech Republic (+420)",
        "code": "420",
        "shortname": "CZ"
      }, {
        "country": "Slovakia (+421)",
        "code": "421",
        "shortname": "SK"
      }, {
        "country": "Liechtenstein (+423)",
        "code": "423",
        "shortname": "LI"
      }, {
        "country": "Falkland Islands (+500)",
        "code": "500",
        "shortname": "FK"
      }, {
        "country": "Belize (+501)",
        "code": "501",
        "shortname": "BZ"
      }, {
        "country": "Guatemala (+502)",
        "code": "502",
        "shortname": "GT"
      }, {
        "country": "El Salvador (+503)",
        "code": "503",
        "shortname": "SV"
      }, {
        "country": "Honduras (+504)",
        "code": "504",
        "shortname": "HN"
      }, {
        "country": "Nicaragua (+505)",
        "code": "505",
        "shortname": "NI"
      }, {
        "country": "Costa Rica (+506)",
        "code": "506",
        "shortname": "CR"
      }, {
        "country": "Panama (+507)",
        "code": "507",
        "shortname": "PA"
      }, {
        "country": "Haiti (+509)",
        "code": "509",
        "shortname": "HT"
      }, {
        "country": "Guadeloupe (+590)",
        "code": "590",
        "shortname": "GP"
      }, {
        "country": "Bolivia (+591)",
        "code": "591",
        "shortname": "BO"
      }, {
        "country": "Guyana (+592)",
        "code": "592",
        "shortname": "GY"
      }, {
        "country": "Ecuador (+593)",
        "code": "593",
        "shortname": "EC"
      }, {
        "country": "French Guiana (+594)",
        "code": "594",
        "shortname": "GF"
      }, {
        "country": "Paraguay (+595)",
        "code": "595",
        "shortname": "PY"
      }, {
        "country": "Martinique (+596)",
        "code": "596",
        "shortname": "MQ"
      }, {
        "country": "Suriname (+597)",
        "code": "597",
        "shortname": "SR"
      }, {
        "country": "Uruguay (+598)",
        "code": "598",
        "shortname": "UY"
      }, {
        "country": "Netherlands Antilles (+599)",
        "code": "599",
        "shortname": "NL"
      }, {
        "country": "Timor-Leste (+670)",
        "code": "670",
        "shortname": "TL"
      }, {
        "country": "Guam (+1671)",
        "code": "1671",
        "shortname": "GU"
      }, {
        "country": "Brunei (+673)",
        "code": "673",
        "shortname": "BN"
      }, {
        "country": "Nauru (+674)",
        "code": "674",
        "shortname": "NR"
      }, {
        "country": "Papua New Guinea (+675)",
        "code": "675",
        "shortname": "PG"
      }, {
        "country": "Tonga (+676)",
        "code": "676",
        "shortname": "TO"
      }, {
        "country": "Solomon Islands (+677)",
        "code": "677",
        "shortname": "SB"
      }, {
        "country": "Vanuatu (+678)",
        "code": "678",
        "shortname": "VU"
      }, {
        "country": "Fiji Islands (+679)",
        "code": "679",
        "shortname": "FJ"
      }, {
        "country": "Cook Islands (+682)",
        "code": "682",
        "shortname": "CK"
      }, {
        "country": "Samoa (+685)",
        "code": "685",
        "shortname": "WS"
      }, {
        "country": "New Caledonia (+687)",
        "code": "687",
        "shortname": "NC"
      }, {
        "country": "French Polynesia (+689)",
        "code": "689",
        "shortname": "PF"
      }, {
        "country": "Korea (+North) (+850)",
        "code": "850",
        "shortname": "KP"
      }, {
        "country": "Hong Kong (+852)",
        "code": "852",
        "shortname": "HK"
      }, {
        "country": "Macao (+853)",
        "code": "853",
        "shortname": "MO"
      }, {
        "country": "Cambodia (+855)",
        "code": "855",
        "shortname": "KH"
      }, {
        "country": "Laos (+856)",
        "code": "856",
        "shortname": "LA"
      }, {
        "country": "Bangladesh (+880)",
        "code": "880",
        "shortname": "BD"
      }, {
        "country": "International (+882)",
        "code": "882",
        "shortname": ""
      }, {
        "country": "Taiwan (+886)",
        "code": "886",
        "shortname": "TW"
      }, {
        "country": "Maldives (+960)",
        "code": "960",
        "shortname": "MV"
      }, {
        "country": "Lebanon (+961)",
        "code": "961",
        "shortname": "LB"
      }, {
        "country": "Jordan (+962)",
        "code": "962",
        "shortname": "JO"
      }, {
        "country": "Syria (+963)",
        "code": "963",
        "shortname": "SY"
      }, {
        "country": "Iraq (+964)",
        "code": "964",
        "shortname": "IQ"
      }, {
        "country": "Kuwait (+965)",
        "code": "965",
        "shortname": "KW"
      }, {
        "country": "Saudi Arabia (+966)",
        "code": "966",
        "shortname": "SA"
      }, {
        "country": "Yemen (+967)",
        "code": "967",
        "shortname": "YE"
      }, {
        "country": "Oman (+968)",
        "code": "968",
        "shortname": "OM"
      }, {
        "country": "Palestine (+970)",
        "code": "970",
        "shortname": "PS"
      }, {
        "country": "United Arab Emirates (+971)",
        "code": "971",
        "shortname": "AE"
      }, {
        "country": "Israel (+972)",
        "code": "972",
        "shortname": "IL"
      }, {
        "country": "Bahrain (+973)",
        "code": "973",
        "shortname": "BH"
      }, {
        "country": "Qatar (+974)",
        "code": "974",
        "shortname": "QA"
      }, {
        "country": "Bhutan (+975)",
        "code": "975",
        "shortname": "BT"
      }, {
        "country": "Mongolia (+976)",
        "code": "976",
        "shortname": "MN"
      }, {
        "country": "Nepal (+977)",
        "code": "977",
        "shortname": "NP"
      }, {
        "country": "Tajikistan (+992)",
        "code": "992",
        "shortname": "TJ"
      }, {
        "country": "Turkmenistan (+993)",
        "code": "993",
        "shortname": "TM"
      }, {
        "country": "Azerbaijan (+994)",
        "code": "994",
        "shortname": "AZ"
      }, {
        "country": "Georgia (+995)",
        "code": "995",
        "shortname": "GE"
      }, {
        "country": "Kyrgyzstan (+996)",
        "code": "996",
        "shortname": "KG"
      }, {
        "country": "Uzbekistan (+998)",
        "code": "998",
        "shortname": "UZ"
      }, {
        "country": "Bahamas (+1242)",
        "code": "1242",
        "shortname": "BS"
      }, {
        "country": "Barbados (+1246)",
        "code": "1246",
        "shortname": "BB"
      }, {
        "country": "Anguilla (+1264)",
        "code": "1264",
        "shortname": "AL"
      }, {
        "country": "Antigua and Barbuda (+1268)",
        "code": "1268",
        "shortname": "AG"
      }, {
        "country": "Virgin Islands, British (+1284)",
        "code": "1284",
        "shortname": "VG"
      }, {
        "country": "Cayman Islands (+1345)",
        "code": "1345",
        "shortname": "KY"
      }, {
        "country": "Bermuda (+1441)",
        "code": "1441",
        "shortname": "BM"
      }, {
        "country": "Grenada (+1473)",
        "code": "1473",
        "shortname": "GD"
      }, {
        "country": "Turks and Caicos Islands (+1649)",
        "code": "1649",
        "shortname": "TC"
      }, {
        "country": "Montserrat (+1664)",
        "code": "1664",
        "shortname": "MS"
      }, {
        "country": "Saint Lucia (+1758)",
        "code": "1758",
        "shortname": "PM"
      }, {
        "country": "Dominica (+1767)",
        "code": "1767",
        "shortname": "DM"
      }, {
        "country": "St. Vincent and The Gren (+1784)",
        "code": "1784",
        "shortname": "VC"
      }, {
        "country": "Puerto Rico (+1787)",
        "code": "1787",
        "shortname": "PR"
      }, {
        "country": "Dominican Republic (+1809)",
        "code": "1809",
        "shortname": "DO"
      }, {
        "country": "Dominican Republic2 (+1829)",
        "code": "1829",
        "shortname": "DO"
      }, {
        "country": "Dominican Republic3 (+1849)",
        "code": "1849",
        "shortname": "DO"
      }, {
        "country": "Trinidad and Tobago (+1868)",
        "code": "1868",
        "shortname": "DO"
      }, {
        "country": "Saint Kitts and Nevis (+1869)",
        "code": "1869",
        "shortname": "KN"
      }, {
        "country": "Jamaica (+1876)",
        "code": "1876",
        "shortname": "JM"
      }, {
        "country": "Congo (+242)",
        "code": "242",
        "shortname": "CG"
      }
    ];
    setupCellphoneValidation = function() {
      var cellPhone;
      cellPhone = document.getElementById("authy-cellphone");
      if (!cellPhone) {
        return;
      }
      cellPhone.onblur = function() {
        if (cellPhone.value !== "" && cellPhone.value.match(/^([0-9][0-9][0-9])\W*([0-9][0-9]{2})\W*([0-9]{0,5})$/)) {
          return cellPhone.style.backgroundColor = "white";
        } else {
          return cellPhone.style.backgroundColor = "#F2DEDE";
        }
      };
    };
    setupAuthyTokenValidation = function() {
      var token;
      token = document.getElementById("authy-token");
      if (!token) {
        return;
      }
      token.onblur = function() {
        if (token.value !== "" && token.value.match(/^\d+$/)) {
          return token.style.backgroundColor = "white";
        } else {
          return token.style.backgroundColor = "#F2DEDE";
        }
      };
    };
    disableAutocompleteAuthyToken = function() {
      var token;
      token = document.getElementById("authy-token");
      if (!token) {
        return;
      }
      token.setAttribute("autocomplete", "off");
    };
    setupTooltip = function() {
      var authy_help, tooltip;
      authy_help = document.getElementById("authy-help");
      if (!authy_help) {
        return;
      }
      tooltip = document.createElement("div");
      tooltip.setAttribute("id", "authy-tooltip");
      tooltip.innerHTML = "<a id=\"authy-tooltip-close\"></a><h3 class=\"tooltip-title\">" + tooltipTitle + "</h3><p class=\"tooltip-content\">" + tooltipMessage + "</p>";
      document.body.appendChild(tooltip);
      document.getElementById("authy-help").onclick = function() {
        tooltip = document.getElementById("authy-tooltip");
        setupTooltipPosition(this, tooltip);
        return tooltip.style.display = "block";
      };
      document.getElementById("authy-tooltip-close").onclick = function() {
        return document.getElementById("authy-tooltip").style.display = "none";
      };
      setupTooltipPosition(authy_help, tooltip);
    };
    setupTooltipPosition = function(helpLink, tooltip) {
      var pos, tooltipLeft, tooltipTop;
      pos = absolutePosFor(helpLink);
      tooltipTop = pos[0];
      tooltipLeft = pos[1] + helpLink.offsetWidth + 5;
      return tooltip.setAttribute("style", "top:" + tooltipTop + "px;left:" + tooltipLeft + "px;");
    };
    processKey40 = function(listId) {
      var activeElement, caId, countriesArr, countriesDropdown, i, li, _i, _len;
      caId = "countries-autocomplete-" + listId;
      countriesDropdown = document.getElementById(caId);
      countriesArr = countriesDropdown.getElementsByTagName("li");
      i = 0;
      for (_i = 0, _len = countriesArr.length; _i < _len; _i++) {
        li = countriesArr[_i];
        if (li.className === "active" && countriesArr.length > (i + 1)) {
          activeElement = countriesArr[i + 1];
          li.className = "";
          setActive(activeElement);
          self.autocomplete(activeElement, false);
          break;
        }
        i++;
      }
      return false;
    };
    processKey38 = function(listId) {
      var activeElement, caId, countriesArr, i;
      caId = "countries-autocomplete-" + listId;
      countriesArr = document.getElementById(caId).getElementsByTagName("li");
      i = countriesArr.length - 1;
      while (i >= 0) {
        if (document.getElementById(caId).getElementsByTagName("li")[i].className === "active") {
          document.getElementById(caId).getElementsByTagName("li")[i].className = "";
          activeElement = null;
          if (i === 0) {
            activeElement = document.getElementById(caId).getElementsByTagName("li")[countriesArr.length - 1];
          } else {
            activeElement = document.getElementById(caId).getElementsByTagName("li")[i - 1];
          }
          setActive(activeElement);
          self.autocomplete(activeElement, false);
          return false;
        }
        i--;
      }
      document.getElementById(caId).getElementsByTagName("li")[0].setAttribute("class", "active");
    };
    processKey13 = function(listId) {
      var obj;
      obj = document.getElementById("countries-autocomplete-" + listId).getElementsByClassName("active")[0];
      self.autocomplete(obj, true);
      return false;
    };
    setActive = function(liElement) {
      var li, liElements, listId, _i, _len;
      listId = liElement.getAttribute("data-list-id");
      liElements = document.getElementById("countries-autocomplete-" + listId).getElementsByTagName("li");
      for (_i = 0, _len = liElements.length; _i < _len; _i++) {
        li = liElements[_i];
        li.className = "";
      }
      return liElement.className = "active";
    };
    setupEvents = function(countriesInput, listId) {
      if (!countriesInput) {
        return;
      }
      countriesInput.onblur = function(event) {
        return processKey13(listId);
      };
      countriesInput.onfocus = function() {
        var countriesDropdown;
        countriesDropdown = document.getElementById("countries-autocomplete-" + listId);
        setupCountriesDropdownPosition(countriesInput, countriesDropdown);
        countriesDropdown.style.display = "block";
      };
      countriesInput.onkeyup = function(event) {
        var keyID;
        document.getElementById("countries-autocomplete-" + listId).style.display = "block";
        keyID = getKeyCode(event);
        switch (keyID) {
          case 13:
            processKey13(listId);
            return false;
          case 40:
            if (processKey40(listId) === false) {
              return false;
            }
            break;
          case 38:
            if (processKey38(listId) === false) {
              return false;
            }
        }
        return self.searchItem(listId);
      };
      countriesInput.onkeypress = function(event) {
        if (getKeyCode(event) === 13) {
          //processKey13(listId);
          return false;
        }
      };
      document.getElementById("countries-autocomplete-" + listId).onclick = function(e) {
        if (e && e.stopPropagation) {
          hideAutocompleteList(listId);
          return e.stopPropagation();
        } else {
          e = window.event;
          return e.cancelBubble = true;
        }
      };
      document.getElementById("countries-input-" + listId).onclick = function(e) {
        if (e && e.stopPropagation) {
          e.stopPropagation();
          countriesInput.focus();
          return countriesInput.select();
        } else {
          e = window.event;
          return e.cancelBubble = true;
        }
      };
      return document.onclick = function() {
        hideAutocompleteList(listId);
      };
    };
    hideAutocompleteList = function(listId) {
      var autocompleteList;
      autocompleteList = document.getElementById("countries-autocomplete-" + listId);
      if (autocompleteList) {
        return autocompleteList.style.display = "none";
      }
    };
    buildItem = function(classActive, country, listId) {
      var cc, flag, li, name;
      cc = country.country.substring(0, 2).toLowerCase() + country.code;
      li = document.createElement("li");
      if (classActive) {
        li.setAttribute("selected", true);
      }
      li.setAttribute("class", classActive);
      li.setAttribute("data-list-id", listId);
      li.setAttribute("rel", country.code);
      li.setAttribute("data-name", country.country);
      li.setAttribute("short-name", country.shortname);
      li.onmouseover = function(event) {
        return setActive(li);
      };
      flag = document.createElement("span");
      flag.setAttribute("class", "aflag flag-" + cc);
      li.appendChild(flag);
      name = document.createElement("span");
      name.innerHTML = country.country;
      li.appendChild(name);
      return li;
    };
    absolutePosFor = function(element) {
      var absLeft, absTop, pos;
      var scrollX, scrollY;    
      absTop = 0;
      absLeft = 0;
      scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
      scrollY = document.documentElement.scrollTop || document.body.scrollTop;  
      //console.log(findPos(element, 0, 0));
      
      /*alert(document.body.clientWidth);
      alert(document.body.clientHeight);
      alert(screen.availWidth);
      alert(screen.availHeight);
      alert(navigator.userAgent);*/
      var mobileKeyWords = new Array('iPhone', 'iPod', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');
      for (var word in mobileKeyWords) {
        if (navigator.userAgent.match(mobileKeyWords[word]) != null) {
          while (element) {
          //pos = $(element).position();
            absTop += element.offsetTop;
            absLeft += element.offsetLeft;
            element = element.offsetParent;
          }
          return [absTop, absLeft];
        }
      }
      pos = findPos(element, scrollX, scrollY).reverse();
      return [pos[0] - 2, pos[1] + 5];
      /*var browserWidth = document.documentElement.clientWidth;
      var browserHeight = document.documentElement.clientHeight;
      var scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
      var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
      var _pos = // should use actual width/height below
          [(browserHeight / 2) - 150 + scrollY, (browserWidth / 2) - 100 + scrollX];
      var _pos = _findPos(element);
      console.log(_pos);
      return _pos;      */            
    };
    function findPos(obj, foundScrollLeft, foundScrollTop) {
      var curleft = 0;
      var curtop = 0;
      if(obj.offsetLeft) curleft += parseInt(obj.offsetLeft);
      if(obj.offsetTop) curtop += parseInt(obj.offsetTop);
      if(obj.scrollTop && obj.scrollTop > 0) {
          curtop -= parseInt(obj.scrollTop);
          foundScrollTop = true;
      }
      if(obj.scrollLeft && obj.scrollLeft > 0) {
          curleft -= parseInt(obj.scrollLeft);
          foundScrollLeft = true;
      }
      if(obj.offsetParent) {
          var pos = findPos(obj.offsetParent, foundScrollLeft, foundScrollTop);
          curleft += pos[0];
          curtop += pos[1];
      } else if(obj.ownerDocument) {
          var thewindow = obj.ownerDocument.defaultView;
          if(!thewindow && obj.ownerDocument.parentWindow)
              thewindow = obj.ownerDocument.parentWindow;
          if(thewindow) {
              if (!foundScrollTop && thewindow.scrollY && thewindow.scrollY > 0) curtop -= parseInt(thewindow.scrollY);
              if (!foundScrollLeft && thewindow.scrollX && thewindow.scrollX > 0) curleft -= parseInt(thewindow.scrollX);
              if(thewindow.frameElement) {
                  var pos = findPos(thewindow.frameElement);
                  curleft += pos[0];
                  curtop += pos[1];
              }
          }
      }

      return [curleft,curtop];
    }
    setupCountriesDropdown = function(countriesSelect, listId) {
      var activeItem, buf, classActive, countries, countriesAutocompleteList, countriesDropdown, countriesInput, countriesInputType, countryCodeValue, i, listItem, name, shortname, placeholder, selectValue;
      if (!countriesSelect) {
        return;
      }
      countries = [];
      i = 0;
      while (i < countriesSelect.getElementsByTagName("option").length) {
        buf = [];
        buf[0] = countriesSelect.getElementsByTagName("option")[i].value;
        buf[1] = countriesSelect.getElementsByTagName("option")[i].innerHTML;
        countries.push(buf);
        i++;
      }
      console.log(countriesSelect);
      countriesSelect.setAttribute("style", "display:none");
      name = countriesSelect.getAttribute("name");
      countriesSelect.removeAttribute("name");
      shortname = countriesSelect.getAttribute("short-name");
      countriesSelect.removeAttribute("short-name");
      countriesDropdown = document.createElement("div");
      countryCodeValue = document.createElement("input");
      countryCodeValue.setAttribute("type", "hidden");
      countryCodeValue.setAttribute("id", "country-code-" + listId);
      countryCodeValue.setAttribute("name", name);
      countryCodeValue.setAttribute("short-name", shortname);
      classActive = "";
      countriesAutocompleteList = document.createElement("ul");
      if (countriesSelect.getAttribute('data-value')) {
        i = 0;
        selectValue = countriesSelect.getAttribute('data-value').replace('+', '');
        while (i < countriesList.length) {
          if (countriesList[i].code === selectValue) {
            activeItem = countriesList[i];
            break;
          }
          i++;
        }
      }
      if (!activeItem) {
        activeItem = countriesList[40];//Japan
      }
      i = 0;
      while (i < countriesList.length) {
        classActive = (activeItem === countriesList[i] ? "active" : "");
        listItem = buildItem(classActive, countriesList[i], listId);
        countriesAutocompleteList.appendChild(listItem);
        if (activeItem === countriesList[i]) {
          activeItem = listItem;
        }
        i++;
      }
      countriesDropdown.innerHTML = "";
      countriesDropdown.appendChild(countriesAutocompleteList);
      document.body.appendChild(countriesDropdown);
      countriesInput = document.createElement("input");
      countriesInput.setAttribute("id", "countries-input-" + listId);
      countriesInput.setAttribute("class", "form-control");
      countriesInput.setAttribute("type", "text");
      countriesInput.setAttribute("autocomplete", "off");
      //countriesInput.setAttribute("readonly", "");
      countriesInput.setAttribute("style", "cursor:pointer;")
      if (countriesSelect.getAttribute("required") !== null) {
        countriesInput.setAttribute("required", "required");
      }
      placeholder = countriesSelect.getAttribute("placeholder");
      if (placeholder != null) {
        countriesSelect.removeAttribute("placeholder");
        countriesInput.setAttribute("placeholder", placeholder);
      }
      countriesInputType = countriesSelect.getAttribute("data-show-as");
      if (/^number/i.exec(countriesInputType)) {
        countriesInput.setAttribute("data-show-as", "number");
      }
      countriesSelect.parentNode.insertBefore(countriesInput, countriesSelect);
      countriesSelect.parentNode.appendChild(countryCodeValue);
      countriesDropdown.setAttribute("id", "countries-autocomplete-" + listId);
      countriesDropdown.setAttribute("class", "countries-autocomplete");
      setupCountriesDropdownPosition(countriesInput, countriesDropdown);
      setupEvents(countriesInput, listId);
      self.autocomplete(activeItem);
    };
    setupCountriesDropdownPosition = function(countriesInput, countriesDropdown) {
      var pos, width;
      pos = absolutePosFor(countriesInput);
      console.log(pos);
      width = countriesInput.offsetWidth;
      if (width < 220) {
        width = 220;
      }
      return countriesDropdown.setAttribute("style", "width: " + (width - 5) + "px; top: " + (pos[0] + 2 + countriesInput.offsetHeight) + "px; left: " + (pos[1] - 2) + "px;");
    };
    findAndSetupCountries = function() {
      var authyCountries, countries, i;
      authyCountries = document.getElementById("authy-countries");
      if (authyCountries) {
        setupCountriesDropdown(authyCountries, 0);
      }
      countries = document.getElementsByClassName("authy-countries");
      i = 0;
      while (i < countries.length) {
        setupCountriesDropdown(countries[i], i + 1);
        i++;
      }
    };
    setCountryField = function() {
      var country, countryCode, defaultListId, field, _i, _len, _results;
      defaultListId = 0;
      field = document.getElementById("authy-countries");
      if (!field) {
        return;
      }
      countryCode = field.value;
      if (countryCode !== '') {
        _results = [];
        for (_i = 0, _len = countriesList.length; _i < _len; _i++) {
          country = countriesList[_i];
          if (country.code === countryCode) {
            self.autocomplete(buildItem('active', country, defaultListId), true);
            break;
          } else {
            _results.push(void 0);
          }
        }
        return _results;
      }
    };
    getKeyCode = function(event) {
      var keyCode;
      if (event && event.which) {
        keyCode = event.which;
      } else if (window.event) {
        keyCode = window.event.keyCode;
      }
      return keyCode;
    };
    this.init = function() {
      setupAuthyTokenValidation();
      disableAutocompleteAuthyToken();
      setupTooltip();
      findAndSetupCountries();
      setCountryField();
      return setupCellphoneValidation();
    };
    this.searchItem = function(listId) {
      var classActive, countriesAutocompleteList, countriesInput, countryItem, countryWords, cw, dropdownMenu, firstCountryCodeFound, firstCountryIsoCode, i, matches, reg, str;
      classActive = "active";
      countriesInput = document.getElementById("countries-input-" + listId);
      str = countriesInput.value;
      countriesAutocompleteList = document.createElement("ul");
      firstCountryCodeFound = null;
      firstCountryIsoCode = null;
      matches = false;
      str = str.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
      reg = new RegExp("^" + str, "i");
      i = 0;
      while (i < countriesList.length) {
        countryItem = countriesList[i];
        countryWords = countryItem.country.toLowerCase().split(/\s+/);
        cw = 0;
        while (cw < countryWords.length) {
          if ((countryWords[cw].length > 2 && countryWords[cw].match(reg)) || ("" + countryItem.code).match(reg)) {
            countriesAutocompleteList.appendChild(buildItem(classActive, countryItem, listId));
            classActive = "";
            matches = true;
            if (firstCountryCodeFound == null) {
              firstCountryCodeFound = countryItem.code;
              firstCountryIsoCode = countryItem.shortname;
            }
            break;
          }
          cw++;
        }
        i++;
      }
      if (matches) {
        dropdownMenu = document.getElementById("countries-autocomplete-" + listId);
        dropdownMenu.innerHTML = "";
        dropdownMenu.appendChild(countriesAutocompleteList);
        self.setCountryCode(listId, firstCountryCodeFound);
        return self.setISO_3661_2(listId, firstCountryIsoCode);
      }
    };
    this.autocomplete = function(obj, hideList) {
      var countriesInput, countryCode, isoCode, listId;
      listId = obj.getAttribute("data-list-id");
      countryCode = obj.getAttribute("rel");     
      isoCode = obj.getAttribute("short-name");
      countriesInput = document.getElementById("countries-input-" + listId);
      if (countriesInput.getAttribute("data-show-as") === "number") {
        countriesInput.value = "+" + countryCode;
      } else {
        countriesInput.value = obj.getAttribute("data-name");
      }
      self.setCountryCode(listId, countryCode);
      self.setISO_3661_2(listId, isoCode);
      self.triggerEvent(listId, isoCode);
      if (hideList) {
        hideAutocompleteList(listId);
      }
    };
    this.setCountryCode = function(listId, countryCode) {
      return document.getElementById("country-code-" + listId).value = countryCode;
    };
    this.setISO_3661_2 = function(listId, isoCode) {
      document.getElementById("country-code-" + listId).setAttribute('short-name', isoCode);
    };
    this.triggerEvent = function(listId, isoCode) {
      if ("createEvent" in document) {
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent("change", false, true);
        document.getElementById("country-code-" + listId).dispatchEvent(evt);
      }
      else
        document.getElementById("country-code-" + listId).fireEvent("onchange");
    }
    this.setTooltip = function(title, msg) {
      var tooltip;
      tooltip = document.getElementById("authy-tooltip");
      if (!tooltip) {
        return;
      }
      tooltip.getElementsByClassName("tooltip-title")[0].innerHTML = title;
      tooltip.getElementsByClassName("tooltip-content")[0].innerHTML = msg;
    };
  };

  Authy.UI.instance = function() {
    if (!this.ui) {
      this.ui = new Authy.UI();
      this.ui.init();
    }
    return this.ui;
  };

  window.onload = function() {
    Authy.UI.instance();
    $('#country-code-0').change(function() {
      getCountryState($(this).attr('short-name'));
    });
    $('#country-code-0').trigger('change');
  };
}).call(this);
