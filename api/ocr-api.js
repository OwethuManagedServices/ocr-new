console.log("ocr-api");
console.log(btoa("Description"));


var OCR = {

V: {


    sApiUrl: "https://api.ocr.lan/v1/",
 //   sApiUrl: "http://localhost/v1/",
	sApiKey: "1234567890",
	sQuickGrd: "abs, fnb",

    oApiHeader : {},
	iStartTime: 0,
	iPingInterval: 0,
	iPingTime: 1500,
	sFolderId: "",
	eFolder: 0,
	eButton: 0,
	sPingUrl: "",
	oResult: {},
},



display: function(){
	var aGrid, aAnomalies, aRecon, eG, eT, eR, eD, iI;
	aGrid = OCR.V.oResult.grid;
	aAnomalies = OCR.V.oResult.anomalies;
	aRecon = OCR.V.oResult.recon;
	eG = document.querySelector("#grids");
	eT = OCR.ele(eG, "", "table");
	eT.border = 1;
	OCR.V.oResult.template.header_fields.forEach(function(sField){
		eR = OCR.ele(eT, "", "tr");
		eD = OCR.ele(eR, "", "td");
		eD.innerHTML = sField;
		eD = OCR.ele(eR, "", "td");
		eD.innerHTML = OCR.V.oResult.header[sField];
	});

	eT = OCR.ele(eG, "", "table");
	eT.border = 1;
	aAnomalies.forEach(function(aRow){
		eR = OCR.ele(eT, "", "tr");
		aRow.forEach(function(sCol){
			eD = OCR.ele(eR, "", "td");
			eD.innerHTML = sCol;
		});
	});
	eT = OCR.ele(eG, "", "table");
	eT.border = 1;
	iI = 0;
	aGrid.forEach(function(aRow){
		eR = OCR.ele(eT, "", "tr");
		var sR = aRow[0];
		var sO = OCR.V.oResult.grid[iI][0];
		if (sR != sO){
			console.log(sR + " != " + sO);
		}
		aRow.forEach(function(sCol){
			eD = OCR.ele(eR, "", "td");
			eD.innerHTML = sCol;
		});
		iI++;
	});
},



ele: function(eParent, sClassName, sType){
	var eX;
	if (!sType){
		sType = "div";
	}
	eX = document.createElement(sType);
	if (sClassName){
		eX.className = sClassName;	
	}
	if (eParent){
		eParent.appendChild(eX);	
	}
	return eX;
},



grid_edit: function(){
	var sPR, sCol, sVal;
	sPR = document.querySelector("#edit_page_row").value;
	sPR = document.querySelector("#edit_column").value;
	sPR = document.querySelector("edit_value").value;
},



grid_from_data: async function(){
	var oResponse, oResult, sUrl;
	OCR.V.sFolderId = OCR.V.eFolder.value;
	OCR.V.oApiHeader = new Headers();
	OCR.V.oApiHeader.append("Authorization", "X-API-KEY " + OCR.V.sApiKey);
	OCR.V.oApiHeader = {
		method: "GET",
		withCredentials: true,
		credentials: "include",
		headers: OCR.V.oApiHeader,
	};
	sUrl = OCR.V.sApiUrl + "statement/" + OCR.V.sFolderId;
	oResponse = await fetch(sUrl, OCR.V.oApiHeader);
	if (oResponse.ok){
		oResult = await oResponse.json();
		console.log(oResult);
		if (!oResult.error){
			OCR.V.oResult = oResult.result;
			document.querySelector("#grids").innerHTML = "";
			OCR.display();
		}
	}
},



init: function(){
	OCR.V.eButton = document.querySelector("#btn_action");
	OCR.V.eFolder = document.querySelector("#folder");
	OCR.V.eFolder.value = new Date().getTime();

},



job: async function(sUrl){
	var oResponse, oResult;
	oResponse = await fetch(sUrl, OCR.V.oApiHeader);
	if (oResponse.ok){
		oResult = await oResponse.json();
		console.log(oResult);
		window.setTimeout(function(){
			OCR.V.eButton.innerHTML = "Start";
			window.clearInterval(OCR.V.iPingInterval);
			if (!oResult.error){
				OCR.V.oResult = oResult.result;
				OCR.display();
			}
		}, 1000);
	}
},



progress: async function(){
	var iTime, eLog, sTime, oResponse, sMessage, sPid, sPagesBank;
	sMessage = "";
	sPid = "";
	oResponse = await fetch(OCR.V.sPingUrl, OCR.V.oApiHeader);
	if (oResponse.ok){
		oResult = await oResponse.json();
		iTime = new Date().getTime();
		eLog = document.querySelector("#log");
		sTime = parseInt((iTime - OCR.V.iStartTime) / 100) / 10;
		if (sTime == parseInt(sTime)){
			sTime += ".0";
		}
		if (oResult.message){
			if (oResult.result.job){
				sMessage = oResult.result.job + ": ";	
			} else {
				sMessage = "";
			}
			sMessage += oResult.message;
		}
		if ((oResult.result) && (oResult.result.pages)){
			sMessage += ", pages: " + oResult.result.pages;
		}
		if ((oResult.result) && (oResult.result.bank)){
			sMessage += ", bank: " + oResult.result.bank;
		}
		sPid = "PID: " + oResult.pid;
		eLog.innerHTML = sTime + " seconds<br>" + sMessage + "<br>" + sPid;
	}
},



start: async function(){
	var sButton, sUrl, sFile;
	sButton = OCR.V.eButton.innerHTML;
	if (sButton == "Start"){
		OCR.V.sFolderId = OCR.V.eFolder.value;
		OCR.V.sPingUrl = OCR.V.sApiUrl + "progress/" + OCR.V.sFolderId;
		OCR.V.iStartTime = new Date().getTime();
		OCR.V.eButton.innerHTML = "Cancel";
		OCR.V.oApiHeader = new Headers();
		OCR.V.oApiHeader.append("Authorization", "X-API-KEY " + OCR.V.sApiKey);
		OCR.V.oApiHeader = {
			method: "GET",
			withCredentials: true,
			credentials: "include",
			headers: OCR.V.oApiHeader,
		};
		sFile = btoa(document.querySelector("#filename").value);
		console.log("file: " + sFile);
		OCR.V.iPingInterval = window.setInterval(OCR.progress, OCR.V.iPingTime);
		sUrl = OCR.V.sApiUrl + "job/" + sFile + "/" + OCR.V.sFolderId;
	} else {
		OCR.V.innerHTML = "Start";
		sUrl = OCR.V.sApiUrl + "cancel/" + OCR.V.sFolderId;
		window.clearInterval(OCR.V.iPingInterval);
	}
	OCR.job(sUrl);
},



};



