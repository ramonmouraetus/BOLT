document.addEventListener("DOMContentLoaded", function () {
    // Define a base de dados para países, códigos de país e linguagem
    const countryData = {
        "Afghanistan": {
            "codigo_pais": "AF",
            "codigo_linguagem": "fa_AF"},
        "Albania": {
            "codigo_pais": "AL",
            "codigo_linguagem": "sq_AL"
        },
        "Algeria": {
            "codigo_pais": "DZ",
            "codigo_linguagem": "ar_DZ"
        },
        "American Samoa": {
            "codigo_pais": "AS",
            "codigo_linguagem": "en_AS"
        },
        "Andorra": {
            "codigo_pais": "AD",
            "codigo_linguagem": "ca_AD"
        },
        "Angola": {
            "codigo_pais": "AO",
            "codigo_linguagem": "pt_AO"
        },
        "Antarctica": {
            "codigo_pais": "AQ",
            "codigo_linguagem": "en_AQ"
        },
        "Antigua and Barbuda": {
            "codigo_pais": "AG",
            "codigo_linguagem": "en_AG"
        },
        "Argentina": {
            "codigo_pais": "AR",
            "codigo_linguagem": "es_AR"
        },
        "Armenia": {
            "codigo_pais": "AM",
            "codigo_linguagem": "hy_AM"
        },
        "Aruba": {
            "codigo_pais": "AW",
            "codigo_linguagem": "nl_AW"
        },
        "Australia": {
            "codigo_pais": "AU",
            "codigo_linguagem": "en_AU"
        },
        "Austria": {
            "codigo_pais": "AT",
            "codigo_linguagem": "de_AT"
        },
        "Azerbaijan": {
            "codigo_pais": "AZ",
            "codigo_linguagem": "az_AZ"
        },
        "Bahamas": {
            "codigo_pais": "BS",
            "codigo_linguagem": "en_BS"
        },
        "Bahrain": {
            "codigo_pais": "BH",
            "codigo_linguagem": "ar_BH"
        },
        "Bangladesh": {
            "codigo_pais": "BD",
            "codigo_linguagem": "bn_BD"
        },
        "Barbados": {
            "codigo_pais": "BB",
            "codigo_linguagem": "en_BB"
        },
        "Belarus": {
            "codigo_pais": "BY",
            "codigo_linguagem": "be_BY"
        },
        "Belgium": {
            "codigo_pais": "BE",
            "codigo_linguagem": "nl_BE"
        },
        "Belize": {
            "codigo_pais": "BZ",
            "codigo_linguagem": "en_BZ"
        },
        "Benin": {
            "codigo_pais": "BJ",
            "codigo_linguagem": "fr_BJ"
        },
        "Bermuda": {
            "codigo_pais": "BM",
            "codigo_linguagem": "en_BM"
        },
        "Bhutan": {
            "codigo_pais": "BT",
            "codigo_linguagem": "dz_BT"
        },
        "Bolivia": {
            "codigo_pais": "BO",
            "codigo_linguagem": "es_BO"
        },
        "Bosnia and Herzegovina": {
            "codigo_pais": "BA",
            "codigo_linguagem": "bs_BA"
        },
        "Botswana": {
            "codigo_pais": "BW",
            "codigo_linguagem": "en_BW"
        },
        "Bouvet Island": {
            "codigo_pais": "BV",
            "codigo_linguagem": "no_BV"
        },
        "Brazil": {
            "codigo_pais": "BR",
            "codigo_linguagem": "pt_BR"
        },
        "British Indian Ocean Territory": {
            "codigo_pais": "IO",
            "codigo_linguagem": "en_IO"
        },
        "Brunei Darussalam": {
            "codigo_pais": "BN",
            "codigo_linguagem": "ms_BN"
        },
        "Bulgaria": {
            "codigo_pais": "BG",
            "codigo_linguagem": "bg_BG"
        },
        "Burkina Faso": {
            "codigo_pais": "BF",
            "codigo_linguagem": "fr_BF"
        },
        "Burundi": {
            "codigo_pais": "BI",
            "codigo_linguagem": "fr_BI"
        },
        "Cambodia": {
            "codigo_pais": "KH",
            "codigo_linguagem": "km_KH"
        },
        "Cameroon": {
            "codigo_pais": "CM",
            "codigo_linguagem": "fr_CM"
        },
        "Canada": {
            "codigo_pais": "CA",
            "codigo_linguagem": "en_CA"
        },
        "Cape Verde": {
            "codigo_pais": "CV",
            "codigo_linguagem": "pt_CV"
        },
        "Cayman Islands": {
            "codigo_pais": "KY",
            "codigo_linguagem": "en_KY"
        },
        "Central African Republic": {
            "codigo_pais": "CF",
            "codigo_linguagem": "fr_CF"
        },
        "Chad": {
            "codigo_pais": "TD",
            "codigo_linguagem": "fr_TD"
        },
        "Chile": {
            "codigo_pais": "CL",
            "codigo_linguagem": "es_CL"
        },
        "China": {
            "codigo_pais": "CN",
            "codigo_linguagem": "zh_CN"
        },
        "Christmas Island": {
            "codigo_pais": "CX",
            "codigo_linguagem": "en_CX"
        },
        "Cocos (Keeling) Islands": {
            "codigo_pais": "CC",
            "codigo_linguagem": "en_CC"
        },
        "Colombia": {
            "codigo_pais": "CO",
            "codigo_linguagem": "es_CO"
        },
        "Comoros": {
            "codigo_pais": "KM",
            "codigo_linguagem": "fr_KM"
        },
        "Congo": {
            "codigo_pais": "CG",
            "codigo_linguagem": "fr_CG"
        },
        "Congo, the Democratic Republic of the": {
            "codigo_pais": "CD",
            "codigo_linguagem": "fr_CD"
        },
        "Cook Islands": {
            "codigo_pais": "CK",
            "codigo_linguagem": "en_CK"
        },
        "Costa Rica": {
            "codigo_pais": "CR",
            "codigo_linguagem": "es_CR"
        },
        "Côte d'Ivoire": {
            "codigo_pais": "CI",
            "codigo_linguagem": "fr_CI"
        },
        "Croatia": {
            "codigo_pais": "HR",
            "codigo_linguagem": "hr_HR"
        },
        "Cuba": {
            "codigo_pais": "CU",
            "codigo_linguagem": "es_CU"
        },
        "Cyprus": {
            "codigo_pais": "CY",
            "codigo_linguagem": "el_CY"
        },
        "Czech Republic": {
            "codigo_pais": "CZ",
            "codigo_linguagem": "cs_CZ"
        },
        "Denmark": {
            "codigo_pais": "DK",
            "codigo_linguagem": "da_DK"
        },
        "Djibouti": {
            "codigo_pais": "DJ",
            "codigo_linguagem": "fr_DJ"
        },
        "Dominica": {
            "codigo_pais": "DM",
            "codigo_linguagem": "en_DM"
        },
        "Dominican Republic": {
            "codigo_pais": "DO",
            "codigo_linguagem": "es_DO"
        },
        "Ecuador": {
            "codigo_pais": "EC",
            "codigo_linguagem": "es_EC"
        },
        "Egypt": {
            "codigo_pais": "EG",
            "codigo_linguagem": "ar_EG"
        },
        "El Salvador": {
            "codigo_pais": "SV",
            "codigo_linguagem": "es_SV"
        },
        "Equatorial Guinea": {
            "codigo_pais": "GQ",
            "codigo_linguagem": "es_GQ"
        },
        "Eritrea": {
            "codigo_pais": "ER",
            "codigo_linguagem": "ti_ER"
        },
        "Estonia": {
            "codigo_pais": "EE",
            "codigo_linguagem": "et_EE"
        },
        "Ethiopia": {
            "codigo_pais": "ET",
            "codigo_linguagem": "am_ET"
        },
        "Falkland Islands (Malvinas)": {
            "codigo_pais": "FK",
            "codigo_linguagem": "en_FK"
        },
        "Faroe Islands": {
            "codigo_pais": "FO",
            "codigo_linguagem": "fo_FO"
        },
        "Fiji": {
            "codigo_pais": "FJ",
            "codigo_linguagem": "en_FJ"
        },
        "Finland": {
            "codigo_pais": "FI",
            "codigo_linguagem": "fi_FI"
        },
        "France": {
            "codigo_pais": "FR",
            "codigo_linguagem": "fr_FR"
        },
        "French Guiana": {
            "codigo_pais": "GF",
            "codigo_linguagem": "fr_GF"
        },
        "French Polynesia": {
            "codigo_pais": "PF",
            "codigo_linguagem": "fr_PF"
        },
        "French Southern Territories": {
            "codigo_pais": "TF",
            "codigo_linguagem": "fr_TF"
        },
        "Gabon": {
            "codigo_pais": "GA",
            "codigo_linguagem": "fr_GA"
        },
        "Gambia": {
            "codigo_pais": "GM",
            "codigo_linguagem": "en_GM"
        },
        "Georgia": {
            "codigo_pais": "GE",
            "codigo_linguagem": "ka_GE"
        },
        "Germany": {
            "codigo_pais": "DE",
            "codigo_linguagem": "de_DE"
        },
        "Ghana": {
            "codigo_pais": "GH",
            "codigo_linguagem": "en_GH"
        },
        "Gibraltar": {
            "codigo_pais": "GI",
            "codigo_linguagem": "en_GI"
        },
        "Greece": {
            "codigo_pais": "GR",
            "codigo_linguagem": "el_GR"
        },
        "Greenland": {
            "codigo_pais": "GL",
            "codigo_linguagem": "kl_GL"
        },
        "Grenada": {
            "codigo_pais": "GD",
            "codigo_linguagem": "en_GD"
        },
        "Guadeloupe": {
            "codigo_pais": "GP",
            "codigo_linguagem": "fr_GP"
        },
        "Guam": {
            "codigo_pais": "GU",
            "codigo_linguagem": "en_GU"
        },
        "Guatemala": {
            "codigo_pais": "GT",
            "codigo_linguagem": "es_GT"
        },
        "Guinea": {
            "codigo_pais": "GN",
            "codigo_linguagem": "fr_GN"
        },
        "Guinea-Bissau": {
            "codigo_pais": "GW",
            "codigo_linguagem": "pt_GW"
        },
        "Guyana": {
            "codigo_pais": "GY",
            "codigo_linguagem": "en_GY"
        },
        "Haiti": {
            "codigo_pais": "HT",
            "codigo_linguagem": "fr_HT"
        },
        "Heard Island and McDonald Islands": {
            "codigo_pais": "HM",
            "codigo_linguagem": "en_HM"
        },
        "Honduras": {
            "codigo_pais": "HN",
            "codigo_linguagem": "es_HN"
        },
        "Hong Kong": {
            "codigo_pais": "HK",
            "codigo_linguagem": "zh_HK"
        },
        "Hungary": {
            "codigo_pais": "HU",
            "codigo_linguagem": "hu_HU"
        },
        "Iceland": {
            "codigo_pais": "IS",
            "codigo_linguagem": "is_IS"
        },
        "India": {
            "codigo_pais": "IN",
            "codigo_linguagem": "hi_IN"
        },
        "Indonesia": {
            "codigo_pais": "ID",
            "codigo_linguagem": "id_ID"
        },
        "Iran, Islamic Republic of": {
            "codigo_pais": "IR",
            "codigo_linguagem": "fa_IR"
        },
        "Iraq": {
            "codigo_pais": "IQ",
            "codigo_linguagem": "ar_IQ"
        },
        "Ireland": {
            "codigo_pais": "IE",
            "codigo_linguagem": "ga_IE"
        },
        "Israel": {
            "codigo_pais": "IL",
            "codigo_linguagem": "he_IL"
        },
        "Italy": {
            "codigo_pais": "IT",
            "codigo_linguagem": "it_IT"
        },
        "Jamaica": {
            "codigo_pais": "JM",
            "codigo_linguagem": "en_JM"
        },
        "Japan": {
            "codigo_pais": "JP",
            "codigo_linguagem": "ja_JP"
        },
        "Jordan": {
            "codigo_pais": "JO",
            "codigo_linguagem": "ar_JO"
        },
        "Kazakhstan": {
            "codigo_pais": "KZ",
            "codigo_linguagem": "kk_KZ"
        },
        "Kenya": {
            "codigo_pais": "KE",
            "codigo_linguagem": "sw_KE"
        },
        "Kiribati": {
            "codigo_pais": "KI",
            "codigo_linguagem": "en_KI"
        },
        "Korea, Democratic People's Republic of": {
            "codigo_pais": "KP",
            "codigo_linguagem": "ko_KP"
        },
        "Korea, Republic of": {
            "codigo_pais": "KR",
            "codigo_linguagem": "ko_KR"
        },
        "Kuwait": {
            "codigo_pais": "KW",
            "codigo_linguagem": "ar_KW"
        },
        "Kyrgyzstan": {
            "codigo_pais": "KG",
            "codigo_linguagem": "ky_KG"
        },
        "Lao People's Democratic Republic": {
            "codigo_pais": "LA",
            "codigo_linguagem": "lo_LA"
        },
        "Latvia": {
            "codigo_pais": "LV",
            "codigo_linguagem": "lv_LV"
        },
        "Lebanon": {
            "codigo_pais": "LB",
            "codigo_linguagem": "ar_LB"
        },
        "Lesotho": {
            "codigo_pais": "LS",
            "codigo_linguagem": "en_LS"
        },
        "Liberia": {
            "codigo_pais": "LR",
            "codigo_linguagem": "en_LR"
        },
        "Libya": {
            "codigo_pais": "LY",
            "codigo_linguagem": "ar_LY"
        },
        "Liechtenstein": {
            "codigo_pais": "LI",
            "codigo_linguagem": "de_LI"
        },
        "Lithuania": {
            "codigo_pais": "LT",
            "codigo_linguagem": "lt_LT"
        },
        "Luxembourg": {
            "codigo_pais": "LU",
            "codigo_linguagem": "lb_LU"
        },
        "Macao": {
            "codigo_pais": "MO",
            "codigo_linguagem": "zh_MO"
        },
        "Macedonia, the Former Yugoslav Republic of": {
            "codigo_pais": "MK",
            "codigo_linguagem": "mk_MK"
        },
        "Madagascar": {
            "codigo_pais": "MG",
            "codigo_linguagem": "fr_MG"
        },
        "Malawi": {
            "codigo_pais": "MW",
            "codigo_linguagem": "en_MW"
        },
        "Malaysia": {
            "codigo_pais": "MY",
            "codigo_linguagem": "ms_MY"
        },
        "Maldives": {
            "codigo_pais": "MV",
            "codigo_linguagem": "dv_MV"
        },
        "Mali": {
            "codigo_pais": "ML",
            "codigo_linguagem": "fr_ML"
        },
        "Malta": {
            "codigo_pais": "MT",
            "codigo_linguagem": "mt_MT"
        },
        "Marshall Islands": {
            "codigo_pais": "MH",
            "codigo_linguagem": "en_MH"
        },
        "Martinique": {
            "codigo_pais": "MQ",
            "codigo_linguagem": "fr_MQ"
        },
        "Mauritania": {
            "codigo_pais": "MR",
            "codigo_linguagem": "ar_MR"
        },
        "Mauritius": {
            "codigo_pais": "MU",
            "codigo_linguagem": "fr_MU"
        },
        "Mayotte": {
            "codigo_pais": "YT",
            "codigo_linguagem": "fr_YT"
        },
        "Mexico": {
            "codigo_pais": "MX",
            "codigo_linguagem": "es_MX"
        },
        "Micronesia, Federated States of": {
            "codigo_pais": "FM",
            "codigo_linguagem": "en_FM"
        },
        "Moldova, Republic of": {
            "codigo_pais": "MD",
            "codigo_linguagem": "ro_MD"
        },
        "Monaco": {
            "codigo_pais": "MC",
            "codigo_linguagem": "fr_MC"
        },
        "Mongolia": {
            "codigo_pais": "MN",
            "codigo_linguagem": "mn_MN"
        },
        "Montenegro": {
            "codigo_pais": "ME",
            "codigo_linguagem": "sr_ME"
        },
        "Montserrat": {
            "codigo_pais": "MS",
            "codigo_linguagem": "en_MS"
        },
        "Morocco": {
            "codigo_pais": "MA",
            "codigo_linguagem": "ar_MA"
        },
        "Mozambique": {
            "codigo_pais": "MZ",
            "codigo_linguagem": "pt_MZ"
        },
        "Myanmar": {
            "codigo_pais": "MM",
            "codigo_linguagem": "my_MM"
        },
        "Namibia": {
            "codigo_pais": "NA",
            "codigo_linguagem": "en_NA"
        },
        "Nauru": {
            "codigo_pais": "NR",
            "codigo_linguagem": "en_NR"
        },
        "Nepal, Federal Democratic Republic of": {
            "codigo_pais": "NP",
            "codigo_linguagem": "ne_NP"
        },
        "Netherlands": {
            "codigo_pais": "NL",
            "codigo_linguagem": "nl_NL"
        },
        "Netherlands Antilles": {
            "codigo_pais": "AN",
            "codigo_linguagem": "nl_AN"
        },
        "New Caledonia": {
            "codigo_pais": "NC",
            "codigo_linguagem": "fr_NC"
        },
        "New Zealand": {
            "codigo_pais": "NZ",
            "codigo_linguagem": "en_NZ"
        },
        "Nicaragua": {
            "codigo_pais": "NI",
            "codigo_linguagem": "es_NI"
        },
        "Niger": {
            "codigo_pais": "NE",
            "codigo_linguagem": "fr_NE"
        },
        "Nigeria": {
            "codigo_pais": "NG",
            "codigo_linguagem": "en_NG"
        },
        "Niue": {
            "codigo_pais": "NU",
            "codigo_linguagem": "en_NU"
        },
        "Norfolk Island": {
            "codigo_pais": "NF",
            "codigo_linguagem": "en_NF"
        },
        "Northern Mariana Islands": {
            "codigo_pais": "MP",
            "codigo_linguagem": "en_MP"
        },
        "Norway": {
            "codigo_pais": "NO",
            "codigo_linguagem": "no_NO"
        },
        "Oman": {
            "codigo_pais": "OM",
            "codigo_linguagem": "ar_OM"
        },
        "Pakistan": {
            "codigo_pais": "PK",
            "codigo_linguagem": "ur_PK"
        },
        "Palau": {
            "codigo_pais": "PW",
            "codigo_linguagem": "en_PW"
        },
        "Palestine, State of": {
            "codigo_pais": "PS",
            "codigo_linguagem": "ar_PS"
        },
        "Panama": {
            "codigo_pais": "PA",
            "codigo_linguagem": "es_PA"
        },
        "Papua New Guinea": {
            "codigo_pais": "PG",
            "codigo_linguagem": "en_PG"
        },
        "Paraguay": {
            "codigo_pais": "PY",
            "codigo_linguagem": "es_PY"
        },
        "Peru": {
            "codigo_pais": "PE",
            "codigo_linguagem": "es_PE"
        },
        "Philippines": {
            "codigo_pais": "PH",
            "codigo_linguagem": "en_PH"
        },
        "Pitcairn": {
            "codigo_pais": "PN",
            "codigo_linguagem": "en_PN"
        },
        "Poland": {
            "codigo_pais": "PL",
            "codigo_linguagem": "pl_PL"
        },
        "Portugal": {
            "codigo_pais": "PT",
            "codigo_linguagem": "pt_PT"
        },
        "Puerto Rico": {
            "codigo_pais": "PR",
            "codigo_linguagem": "es_PR"
        },
        "Qatar": {
            "codigo_pais": "QA",
            "codigo_linguagem": "ar_QA"
        },
        "Réunion": {
            "codigo_pais": "RE",
            "codigo_linguagem": "fr_RE"
        },
        "Romania": {
            "codigo_pais": "RO",
            "codigo_linguagem": "ro_RO"
        },
        "Russian Federation": {
            "codigo_pais": "RU",
            "codigo_linguagem": "ru_RU"
        },
        "Rwanda": {
            "codigo_pais": "RW",
            "codigo_linguagem": "rw_RW"
        },
        "Saint Helena": {
            "codigo_pais": "SH",
            "codigo_linguagem": "en_SH"
        },
        "Saint Kitts and Nevis": {
            "codigo_pais": "KN",
            "codigo_linguagem": "en_KN"
        },
        "Saint Lucia": {
            "codigo_pais": "LC",
            "codigo_linguagem": "en_LC"
        },
        "Saint Pierre and Miquelon": {
            "codigo_pais": "PM",
            "codigo_linguagem": "fr_PM"
        },
        "Saint Vincent and the Grenadines": {
            "codigo_pais": "VC",
            "codigo_linguagem": "en_VC"
        },
        "Samoa": {
            "codigo_pais": "WS",
            "codigo_linguagem": "sm_WS"
        },
        "San Marino": {
            "codigo_pais": "SM",
            "codigo_linguagem": "it_SM"
        },
        "Sao Tome and Principe": {
            "codigo_pais": "ST",
            "codigo_linguagem": "pt_ST"
        },
        "Saudi Arabia": {
            "codigo_pais": "SA",
            "codigo_linguagem": "ar_SA"
        },
        "Senegal": {
            "codigo_pais": "SN",
            "codigo_linguagem": "fr_SN"
        },
        "Serbia": {
            "codigo_pais": "RS",
            "codigo_linguagem": "sr_RS"
        },
        "Seychelles": {
            "codigo_pais": "SC",
            "codigo_linguagem": "fr_SC"
        },
        "Sierra Leone": {
            "codigo_pais": "SL",
            "codigo_linguagem": "en_SL"
        },
        "Singapore": {
            "codigo_pais": "SG",
            "codigo_linguagem": "en_SG"
        },
        "Slovakia": {
            "codigo_pais": "SK",
            "codigo_linguagem": "sk_SK"
        },
        "Slovenia": {
            "codigo_pais": "SI",
            "codigo_linguagem": "sl_SI"
        },
        "Solomon Islands": {
            "codigo_pais": "SB",
            "codigo_linguagem": "en_SB"
        },
        "Somalia": {
            "codigo_pais": "SO",
            "codigo_linguagem": "so_SO"
        },
        "South Africa": {
            "codigo_pais": "ZA",
            "codigo_linguagem": "en_ZA"
        },
        "South Georgia and the South Sandwich Islands": {
            "codigo_pais": "GS",
            "codigo_linguagem": "en_GS"
        },
        "South Sudan": {
            "codigo_pais": "SS",
            "codigo_linguagem": "en_SS"
        },
        "Spain": {
            "codigo_pais": "ES",
            "codigo_linguagem": "es_ES"
        },
        "Sri Lanka": {
            "codigo_pais": "LK",
            "codigo_linguagem": "si_LK"
        },
        "Sudan": {
            "codigo_pais": "SD",
            "codigo_linguagem": "ar_SD"
        },
        "Suriname": {
            "codigo_pais": "SR",
            "codigo_linguagem": "nl_SR"
        },
        "Svalbard and Jan Mayen": {
            "codigo_pais": "SJ",
            "codigo_linguagem": "no_SJ"
        },
        "Swaziland": {
            "codigo_pais": "SZ",
            "codigo_linguagem": "en_SZ"
        },
        "Sweden": {
            "codigo_pais": "SE",
            "codigo_linguagem": "sv_SE"
        },
        "Switzerland": {
            "codigo_pais": "CH",
            "codigo_linguagem": "de_CH"
        },
        "Syrian Arab Republic": {
            "codigo_pais": "SY",
            "codigo_linguagem": "ar_SY"
        },
        "Taiwan": {
            "codigo_pais": "TW",
            "codigo_linguagem": "zh_TW"
        },
        "Tajikistan": {
            "codigo_pais": "TJ",
            "codigo_linguagem": "tg_TJ"
        },
        "Tanzania, United Republic of": {
            "codigo_pais": "TZ",
            "codigo_linguagem": "sw_TZ"
        },
        "Thailand": {
            "codigo_pais": "TH",
            "codigo_linguagem": "th_TH"
        },
        "Timor-Leste": {
            "codigo_pais": "TL",
            "codigo_linguagem": "pt_TL"
        },
        "Togo": {
            "codigo_pais": "TG",
            "codigo_linguagem": "fr_TG"
        },
        "Tokelau": {
            "codigo_pais": "TK",
            "codigo_linguagem": "en_TK"
        },
        "Tonga": {
            "codigo_pais": "TO",
            "codigo_linguagem": "to_TO"
        },
        "Trinidad and Tobago": {
            "codigo_pais": "TT",
            "codigo_linguagem": "en_TT"
        },
        "Tunisia": {
            "codigo_pais": "TN",
            "codigo_linguagem": "ar_TN"
        },
        "Turkey": {
            "codigo_pais": "TR",
            "codigo_linguagem": "tr_TR"
        },
        "Turkmenistan": {
            "codigo_pais": "TM",
            "codigo_linguagem": "tk_TM"
        },
        "Turks and Caicos Islands": {
            "codigo_pais": "TC",
            "codigo_linguagem": "en_TC"
        },
        "Tuvalu": {
            "codigo_pais": "TV",
            "codigo_linguagem": "en_TV"
        },
        "Uganda": {
            "codigo_pais": "UG",
            "codigo_linguagem": "en_UG"
        },
        "Ukraine": {
            "codigo_pais": "UA",
            "codigo_linguagem": "uk_UA"
        },
        "United Arab Emirates": {
            "codigo_pais": "AE",
            "codigo_linguagem": "ar_AE"
        },
        "United Kingdom": {
            "codigo_pais": "GB",
            "codigo_linguagem": "en_GB"
        },
        "United States": {
            "codigo_pais": "US",
            "codigo_linguagem": "en_US"
        },
        "United States Minor Outlying Islands": {
            "codigo_pais": "UM",
            "codigo_linguagem": "en_UM"
        },
        "Uruguay": {
            "codigo_pais": "UY",
            "codigo_linguagem": "es_UY"
        },
        "Uzbekistan": {
            "codigo_pais": "UZ",
            "codigo_linguagem": "uz_UZ"
        },
        "Vanuatu": {
            "codigo_pais": "VU",
            "codigo_linguagem": "en_VU"
        },
        "Venezuela": {
            "codigo_pais": "VE",
            "codigo_linguagem": "es_VE"
        },
        "Viet Nam": {
            "codigo_pais": "VN",
            "codigo_linguagem": "vi_VN"
        },
        "Virgin Islands, British": {
            "codigo_pais": "VG",
            "codigo_linguagem": "en_VG"
        },
        "Virgin Islands, U.S.": {
            "codigo_pais": "VI",
            "codigo_linguagem": "en_VI"
        },
        "Wallis and Futuna": {
            "codigo_pais": "WF",
            "codigo_linguagem": "fr_WF"
        },
        "Western Sahara": {
            "codigo_pais": "EH",
            "codigo_linguagem": "ar_EH"
        },
        "Yemen": {
            "codigo_pais": "YE",
            "codigo_linguagem": "ar_YE"
        },
        "Zambia": {
            "codigo_pais": "ZM",
            "codigo_linguagem": "en_ZM"
        },
        "Zimbabwe": {
            "codigo_pais": "ZW",
            "codigo_linguagem": "en_ZW"
        }
    };

    // Função que retorna o código do país com base no nome do país
    function getCountryCode(countryName) {
        const countryInfo = countryData[countryName];
        return countryInfo ? countryInfo.codigo_pais.toLowerCase() : null;
    }

// Função para aguardar um elemento aparecer no DOM
    function waitForElement(selector, callback) {
        const element = document.querySelector(selector);
        if (element) {
            callback(element);
        } else {
            setTimeout(() => waitForElement(selector, callback), 100); // Tenta novamente a cada 100ms
        }
    }

// Função para criar e adicionar a bandeira e o nome do país ao header da linha
    function createCountryInfoSpan(countryName, countryCode) {
        const span = document.createElement('span');
        span.classList.add('country-info');
        span.textContent = countryName;

        const flagIcon = document.createElement('i');
        flagIcon.classList.add('flag-icon', `flag-icon-${countryCode}`);
        flagIcon.style.marginRight = '8px';
        flagIcon.style.width = '24px';
        flagIcon.style.height = '15px'; // Espaçamento entre a bandeira e o nome

// Espaçamento entre a bandeira e o nome

        span.prepend(flagIcon); // Insere a bandeira antes do nome do país
        return span;
    }

// Função para adicionar o span de informação do país no header da linha
    function addCountryInfoToElement(element) {
        const select2Span = element.querySelector('.select2-selection__rendered');
        if (!select2Span) {
            console.error(`Elemento Select2 não encontrado para a linha ${element.getAttribute('data-id')}`);
            return;
        }

        const countryName = select2Span.textContent || select2Span.innerText;
        const countryCode = getCountryCode(countryName);
        if (!countryCode) {
            console.error(`Código de país não encontrado para ${countryName}`);
            return;
        }

        const headerContainer = element.querySelector('.acf-row-handle');
        if (!headerContainer) {
            console.error(`Header não encontrado para a linha ${element.getAttribute('data-id')}`);
            return;
        }

        if (!element.querySelector('span.country-info')) {
            const countryInfoSpan = createCountryInfoSpan(countryName, countryCode);
            headerContainer.style.position = 'relative';
            countryInfoSpan.style.position = 'absolute';
            countryInfoSpan.style.top = '8px';
            countryInfoSpan.style.right = countryName.length >= 7 ? '-117px' : '-73px';
            countryInfoSpan.style.zIndex = '9999';

            headerContainer.appendChild(countryInfoSpan);
        }
    }

// Função para controlar a visibilidade da informação do país com base no colapso
    function toggleCountryInfoVisibility() {
        const rows = document.querySelectorAll('[data-id^="row-"]');
        rows.forEach((row) => {
            const countryInfoSpan = row.querySelector('span.country-info');
            const isCollapsed = row.classList.contains('-collapsed');

            if (isCollapsed && !countryInfoSpan) {
                addCountryInfoToElement(row);
            } else if (!isCollapsed && countryInfoSpan) {
                countryInfoSpan.style.display = 'none';
            } else if (isCollapsed && countryInfoSpan) {
                countryInfoSpan.style.display = 'block';
            }
        });
    }

// Função para monitorar as mudanças de colapso nas linhas
    function monitorCollapsingRows() {
        toggleCountryInfoVisibility();

        // Adiciona um listener aos cabeçalhos para monitorar colapso/expansão
        const headers = document.querySelectorAll('.acf-row-handle');
        headers.forEach((header) => {
            header.addEventListener('click', () => {
                setTimeout(toggleCountryInfoVisibility, 50);
            });
        });
    }

// Inicializa quando o documento estiver pronto
    window.onload = () => {
        waitForElement('.select2-selection__rendered', monitorCollapsingRows);
    };

});

