console.log("APP");
var APP = {
_V: {
	oAjax: {
		bDone: 0,
		bRaw: 0,
		eSpinnerParent: 0,
		eSpinnerParentOrig: 0,
		bKeepSpinner: 0,
	},
	oSrv: {
		action: "acc-ajax",
		ajaxurl: "/ajax",
		token: "",
		url: "",
	},
	sLogo: "",
	oTheme : {},
},



ajax: function(oData, fCallback, sType = "post"){
	var sData, oX, oIn, eC, eA;
    eA = document.getElementsByName("csrf-token")[0];
    APP._V.oSrv.token = eA.content;
    APP._V.oSrv.url = window.location.origin;
	if (APP._V.oAjax.eSpinnerParent){
		eC = APP.dg("acc_spinner");
		APP._V.oAjax.eSpinnerParentOrig = eC.parentNode;
		APP._V.oAjax.eSpinnerParent.appendChild(eC);
		eC.style.display = "block";
	}
	if (APP._V.oAjax.eButtonMask){
		APP._V.oAjax.eButtonMask.style.display = "block";
	}
	oIn = oData;
	APP._V.oAjax.bDone = 0;
	APP._V.oAjax.bRaw = oData.raw;
	oData = APP.json(0, oData);
	oData = "action=" + APP._V.oSrv.action + "&_token=" + APP._V.oSrv.token +
		"&data=" + APP.base64(oData);
	oX = new XMLHttpRequest();
	oX.open(sType, APP._V.oSrv.url + APP._V.oSrv.ajaxurl);
	oX.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	try {
		oX.send(oData);
	} catch(error){
		if (fCallback){
			fCallback({error:error});
		}
	}	
	oX.onreadystatechange = function (){
		if (oX.readyState == 4){
			if (APP._V.oAjax.eButtonMask){
				APP._V.oAjax.eButtonMask.style.display = "none";
			}
			if (eC){
				eC.style.display = "none";
				eSpinnerParent: 0,
				APP._V.oAjax.eSpinnerParentOrig.appendChild(eC);
			}
			APP._V.oAjax.eButtonMask = 0;
			APP._V.oAjax.eSpinnerParent = 0;
			if (oX.status == 200){
				sData = oX.responseText;
				if (!APP._V.oAjax.bRaw){
					sData = APP.json(APP.base64(0, sData));
				}
				if (fCallback){
					fCallback(sData);
				} else {
					APP.gcn("mainpage").innerHTML = sData.html;
					if (sData.url){
						window.history.pushState(null, null, sData.url);
					}
				}
			} else {
				if (fCallback){
					oIn.error = oX;
					fCallback(oIn);
				}
			}
		}
	}
},



ascii: function(sStr){
	if (!sStr){
		sStr = "";
	}
	return unescape(encodeURIComponent(sStr));
},



base64: function(sEncode, sDecode){
	if ((!sEncode) && (!sDecode)){
		return "";
	}
	if (sEncode){
		return window.btoa(sEncode);
	} else {
		return window.atob(sDecode);
	}
},



color: function(oEvent){
	var eP, aP;
	eP = oEvent.target.parentNode.parentNode;
	aP = eP.childNodes;
	aP[0].value = (document.getElementById("clr-color-value").value);
},



currency: function(iAmount){
	var oF;
	oF = new Intl.NumberFormat('en-ZA', {
		style: 'currency',
		currency: 'ZAR',
		maximumFractionDigits: 2,
	});
	return(oF.format(iAmount));
},



deleteconfirm: function(oEvent, sMsg){
	var eA;
	if (!sMsg){
		sMsg = "";
	}
	eA = oEvent.target;
	if (confirm("Click OK to delete this record. " + sMsg)){
		while ((eA) && (eA.type != "button")){
			eA = eA.parentNode;
		}
		if (eA){
			eA.type = "submit";
			eA = eA.parentNode;
			eA.submit();
		}
	}
},



dg: function(sId){
	return document.getElementById(sId);
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



gcn:function(sClassName, bAll, bIsSvg){
	var iI, aReturn, iSize, aRet1, oRec;
	aReturn = document.getElementsByClassName(sClassName);
	if (!aReturn.length){
		aReturn = [0];
	}
	if (bAll){
		iSize = aReturn.length;
		aRet1 = [];
		for (iI = 0; iI < iSize; iI++){
			oRec = aReturn[iI];
			if (oRec) {
				if (!bIsSvg){
					if (oRec.className.indexOf(sClassName) != -1){
						aRet1.push(oRec);
					}
				} else {
					if (oRec.className.baseVal.indexOf(sClassName) != -1){
						aRet1.push(oRec);
					}
				}
			}
		}
		return aRet1;
	} else {
		return aReturn[0];
	}
},



getparams: function(sGet, sValue){
	var aL, iF, iI, aM, sW, sP;
	aL = window.location.search.substr(1).split("&");
	if ((aL.length) && (aL[0])){
		iF = 0;
		for (iI = 0; iI < aL.length; iI++){
			aM = aL[iI].split("=");
			if (aM[0] == sGet){
				sW = decodeURIComponent(aM[1]);
				if (sValue){
					aL[iI] = aM[0] + "=" + encodeURIComponent(sValue);
				} else {
					return [aM[1], 1];
				}
				iF = iI + 1;
			}
		}
		if ((!iF) && (sValue)){
			aL.push(sGet + "=" + encodeURIComponent(sValue));
		}
		sP = "?" + aL.join("&");
	} else {
		if (sValue){
			sP = "?" + sGet + "=" + encodeURIComponent(sValue);
		} else {
			sP = 0;
		}
	}
	return [sP, iF];
},



init: function(sTheme){
    var eA, eO, aS, iI, iJ, aP, sBg;
	APP._V.oTheme = JSON.parse(atob(sTheme));
    eA = document.getElementsByName("csrf-token")[0];
    APP._V.oSrv.token = eA.content;
    APP._V.oSrv.url = window.location.origin;
	if (APP.dg("tabpage_1")){
		OCR.tabpage({target: document.querySelector("#tabbutton_1")});
	}
	aS = APP.getparams("search");
	if (aS[1]){
		GRD.searchtype({target: APP.dg("search")});
	}
	aS = APP.getparams("filter");
	if (aS[1]){
		eA = document.getElementById("filter");
		if (eA){
			iJ = 0;
			for (iI = 0; iI < eA.options.length; iI++){
				eO = eA.options[iI];
				if (eO.innerText.toLowerCase() == aS[0]){
					iJ = iI + 1;
					break;
				}
			}
			if (iJ){
				eA.selectedIndex = iJ - 1;
			}
		}
	}

},



initheader: function(sHtml, sHtml2, sHtml3){
	var eA, iH;
	iH = 32;
	if (sHtml){
		eA = APP.dg("main-header");
		eA.innerHTML = atob(sHtml);
		iH += 48;
	}
	if (sHtml2){
		eA = APP.dg("main-header2");
		eA.innerHTML = atob(sHtml2);
		iH += 35;
	}
	if (sHtml3){
		eA = APP.dg("main-header2").parentNode;
		eA = APP.ele(eA, "main-header3");
		eA.innerHTML = atob(sHtml3);
		iH += 35;
	}
	eA = APP.dg("main-header-height");
	eA.style.height = iH + "px";
},



json:function(sKey, sValue){
	var sReturn = "";
	if (sValue){
		sReturn = JSON.stringify(sValue);
	} else {
		if (sKey){
			sReturn = JSON.parse(sKey);
		} else {
			sReturn = "";
		}
	}
	return sReturn;
},



jsonscan: function(obj, bSort) {
	var iK;
	if (obj instanceof Object){
		for (iK in obj){
			if (obj.hasOwnProperty(iK)){
				WST._V.aObjScan.push([iK, obj[iK]]);
				//recurse
				APP.jsonscan(obj[iK]);  
			}                
		}
	} else {
	};
	if (bSort){
		function compareFn(a, b){
			if (a < b){
				return -1;
			} else if (a > b) {
				return 1;
			}
			return 0;
		}
		WST._V.aObjScan.sort(compareFn);
	}
},



logout: function(){
	APP.dg("logoutsubmit").click();
},



scrollto: function(sId){
	var eA, iT;
	eA = document.getElementById(sId);
	if (eA){
		iT = eA.offsetTop;
		window.scrollTo({top: (iT - 130), behavior: "smooth"});
	}
},



themeswitch: function(bLoggedIn){
	var eH;
	eH = document.getElementsByTagName("html")[0];
	if (eH.className != "dark"){
		eH.className = "dark";
		if (!bLoggedIn){
			APP.dg("theme_moon").style.display = "block";
			APP.dg("theme_sun").style.display = "none";
		}
	} else {
		eH.className = "";
		if (!bLoggedIn){
			APP.dg("theme_moon").style.display = "none";
			APP.dg("theme_sun").style.display = "block";
		}
	}
	console.log((document.title.split("--")[0]).replace(/ /g, ""));
	localStorage.setItem((document.title.split("--")[0]).replace(/ /g, ""),
		JSON.stringify({theme: eH.className}));
	if (bLoggedIn){
		APP.themeswitchloggedin();
	}
},



themeswitchloggedin: function(){
	var eA, sClass;
	eA = APP.dg("darkmode");
	console.log(eA);
	if (eA.checked){
		eA.checked = "";
		sClass = "";
	} else {
		sClass = "dark";
		eA.checked = "checked";
	}
	APP.ajax({
		job: "darkmode",
		class: sClass,
	}, function(oData){
		console.log(oData);
		eA = document.getElementsByTagName("html")[0];
		eA.className = sClass;
		if (window.location.href.indexOf('dashboard') != -1){
			window.location.reload();
		}
	})
},



uppercasefirst(sString){
	var iI;
	var aSplitStr = sString.toLowerCase().split("_");
	for (iI = 0; iI < aSplitStr.length; iI++){
		aSplitStr[iI] = aSplitStr[iI].charAt(0).toUpperCase() + aSplitStr[iI].substring(1);     
	}
	return aSplitStr.join(' '); 
},



videoplay: function(iNum){
	var eD, eI, sS;
	eD = document.getElementById("video_" + iNum);
	sS = eD.getAttribute("src");
	eI = document.createElement("iframe");
	eI.src = sS;
	eI.width = eD.clientWidth / 1.3;
	eI.height = eD.clientHeight / 1.3;
	eD.appendChild(eI);
},



};



var ACL = {

member: function(bMember){
	var aM, aN, eA, eB, eO, eP, iI, iSM, iSN;
	iSM = 0;
	iSN = 0;
	aM = [];
	aN = [];
	eA = APP.dg("selmember");
	for (iI = 0; iI < eA.options.length; iI++){
		eO = eA.options[iI];
		if (eO.selected){
			iSM = iI + 1;
		}
		aM.push([eO.value, eO.textContent]);
	}

	eB = APP.dg("selnotmember");
	for (iI = 0; iI < eB.options.length; iI++){
		eO = eB.options[iI];
		if (eO.selected){
			iSN = iI + 1;
		}
		aN.push([eO.value, eO.textContent]);
	}
	if (bMember){
		if (iSN){
			eO = eB.options[iSN - 1];
			eP = APP.ele(eA, "", "option");
			eP.value = eO.value;
			eP.textContent = eO.textContent;
			eO.parentNode.removeChild(eO);
		}
	} else {
		if (iSM){
			eO = eA.options[iSM - 1];
			eP = APP.ele(eB, "", "option");
			eP.value = eO.value;
			eP.textContent = eO.textContent;
			eO.parentNode.removeChild(eO);
		}
	}
	aM = [];
	aN = [];
	eA = APP.dg("selmember");
	for (iI = 0; iI < eA.options.length; iI++){
		eO = eA.options[iI];
		if (eO.selected){
			iSM = iI + 1;
		}
		aM.push([eO.value, eO.textContent]);
	}

	eB = APP.dg("selnotmember");
	for (iI = 0; iI < eB.options.length; iI++){
		eO = eB.options[iI];
		if (eO.selected){
			iSN = iI + 1;
		}
		aN.push([eO.value, eO.textContent]);
	}
	APP.dg("memberof").value = JSON.stringify(aM);
	APP.dg("notmemberof").value = JSON.stringify(aN);
},


};



var GRD = {

V: {

},


/*
filter: function(oEvent){
	var eA, aL, aM, sS, eA, sU, sP, sV, aL, aM;
	sS = "pg=";
	aM = [];
	aL = window.location.search.substr(1).split("&");
	aL.forEach(function(sL){
		if ((sL) && (sL.indexOf(sS) == -1)){
			aM.push(sL);
		}
	});
	if (aM.length){
		aM[0] = "?" + aM[0];
	}
	sS = window.location.href.split("?")[0] + aM.join("&");
	window.history.pushState({pageTitle: window.title}, "", sS);

	eA = document.getElementById("filter");
	sV = eA[eA.value - 1].innerHTML;
	sU = window.location.href.split("?")[0];
	sP = APP.getparams("filter", sV).toLowerCase()[0];
	if (sP == '?filter=all'){
		window.location.href = sU;
	} else {
		if (sP){
			window.location.href = sU + sP;
		}
	}
},
*/



filtershow: function(oEvent){
	var eB;
	eB = APP.dg("acc_filter_box");
	if (eB.style.height == "300px"){
		eB.style.height = "0px";
	} else {
		eB.style.height = "300px";
	}
},





searchclear: function(oEvent){
	var eA, aL, aM, sS;
	eA = document.getElementById("search");
	sS = "search=";
	aM = [];
	aL = window.location.search.substr(1).split("&");
	aL.forEach(function(sL){
		if (sL.indexOf(sS) == -1){
			aM.push(sL);
		}
	});
	if (aM.length){
		aM[0] = "?" + aM[0];
	}
	sS = window.location.href.split("?")[0] + aM.join("&");
	window.history.pushState({pageTitle: window.title}, "", sS);
	console.log('c',sS);
	eA.value = "";
	eA.focus();
	GRD.searchtype({target: eA});
},



searchenter: function(oEvent){
	if (oEvent.charCode == 13){
		GRD.searchgo(oEvent);
	}
	console.log('e',oEvent.target);
},



searchgo: function(oEvent){
	var eA, aL, aM, sS, eA, sU, sP, sV, aL, aM;
	sS = "pg=";
	aM = [];
	aL = window.location.search.substr(1).split("&");
	aL.forEach(function(sL){
		if ((sL) && (sL.indexOf(sS) == -1)){
			aM.push(sL);
		}
	});
	if (aM.length){
		aM[0] = "?" + aM[0];
	}
	sS = window.location.href.split("?")[0] + aM.join("&");
	window.history.pushState({pageTitle: window.title}, "", sS);

	eA = document.getElementById("search");
	sV = eA.value;
	sU = window.location.href.split("?")[0];
	sP = APP.getparams("search", sV)[0];
	if (sP){
		window.location.href = sU + sP;
	}
},



searchtype: function(oEvent){
	var aTd, iI, eA, sI, sV, sG, sC;
	eA = oEvent.target;
		if (eA){
		sV = eA.value.toUpperCase();
		sC = "bg-yellow";
		aTd = APP.gcn(sC, 1);
		aTd.forEach(function(eT){
			eT.className = "";
		}); 
		if (!sV){
			return;
		}
		aTd = document.getElementsByTagName("td");
		for (eA of aTd){
			sG = eA.innerText.toUpperCase();
			if (sG.indexOf(sV) != -1){
				eA. className = sC;
			}
		}
	}
},



sort: function(sSort){
	var sP, sU;
	sU = window.location.href.split("?")[0];
	sP = APP.getparams("sort", sSort)[0];
	console.log(sSort);
	window.location.href = sU + sP;
},



};

	

var MBF = {
	_V: {
		aFilesUpload: ['', '', '', '', '', ''],
		aImports: [],
		aUploadInfo: [],
		iImportsLength: 0,
		iUploadChunkNow: 0,
		iUploadChunkSize: 10240,
		iUploadChunksTotal: 0,
		oUploadData: 0,
		sFileImg: "",
		sUploadFileExt: "",
		sUploadFileName: "",
		sUploadGet: "",
		sUploadId: "",
		sUploadIdPart: "",
		sUploadOriginalFileName: "",
		sCategory: "",
		iFileNow: 0,
		aFiles: [],
		sId: "",
},



selected: function(oEvent){
	var eA, eF, aId, iI;
	eA = oEvent.target;
	aId = eA.id.split("_");
	MBF._V.sCategory = aId[2];
	eF = APP.dg("acc_fileupload_" + aId[2] + "_selected");
	eF.innerHTML = "";
	iI = 0;
	while (eA.files[iI]){
		eF.innerHTML += (eA.files[iI].name + "&nbsp;&nbsp;&nbsp;");
		MBF._V.aFiles.push(eA.files[iI]);
		iI++;
	}
	MBF._V.iFileNow = 0;
},



uploadcancel: function(oData){
	var eA;
	eA = APP.dg("acc_uploadprogressbar_" + oData.part);
	eA.style.width = 0;
	eA.parentNode.style.opacity = 0;
},



uploaddone: function(oData){
	var eA, eB, iI, aA, sA;
	eA = APP.dg("acc_uploadprogressbar_" + MBF._V.sUploadIdPart);
	if (oData.error){
		iI = 6000;
		eA.style.background = "#f00";
		eA.style.color = "#fff";
	} else {
		iI = 2000;
		eA.style.background = "";
		eA.style.color = "#222";
	}
	if (oData.convert){
		eA = APP.dg("theme_logo");
		if (eA){
			eA.src = oData.convert.src;
			APP._V.sLogo = oData.convert.src;
		}

	}
	MBF._V.sId = oData.folder;
	MBF._V.iFileNow++;
	if (MBF._V.aFiles[MBF._V.iFileNow]){
		eB = APP.dg("acc_upload_statement");
		eB.innerHTML = eB.innerHTML1;
		MBF.uploadstart({target: eB});
	} else {
		eA = APP.dg("acc_uploadprogressbar_" + MBF._V.sUploadIdPart)
		eA.style.width = 0;
		eA.parentNode.style.opacity = 0;
		if (oData.step == 1){
			eA = APP.dg("processingbox");
			eA.style.display = "block";
			document.querySelector("#log").innerHTML = "<br><br><br><br>";
			eA = APP.dg("processingbtn");
			OCR.V.eButton = APP.dg("btn_action");
			OCR.V.eButton
			OCR.V.eFolder = APP.dg("folder");
			OCR.V.eFolder.value = oData.folder;
			eA = document.querySelector("#uploadbox");
			eA.style.display = "none";
			OCR.start(oData.b64_original_filename);
		}
	}
},



uploadprogress: function(){
	var oC, eA, sF, aR, iW;
	oC = MBF._V.oUploadData.slice
		(MBF._V.iUploadChunkNow * MBF._V.iUploadChunkSize, 
		(MBF._V.iUploadChunkNow + 1) * MBF._V.iUploadChunkSize);
	if (MBF._V.iUploadChunkNow < MBF._V.iUploadChunksTotal){
		var oReq = new XMLHttpRequest();

		oReq.open("POST", APP._V.oSrv.url + "/upload.php" 
			+ "?file=" + MBF._V.sUploadFileName + MBF._V.sUploadGet, true);
		oReq.onload = function (oEvent) {
			aR = (oEvent.target.responseText).split("|");
			MBF._V.aUploadInfo = aR;
			iFileSize = aR[0];
			MBF._V.sUploadFileExt = aR[1];
			iW = (iFileSize * 100 / MBF._V.oUploadData.byteLength);
			if (iW > 100){
				iW = 100
			}
			APP.dg("acc_uploadprogressbar_" 
				+ MBF._V.sUploadIdPart).style.width = iW + "%";
			MBF.uploadprogress();
		};
		var uInt8Array = new Uint8Array(oC);
		oReq.send(uInt8Array.buffer);
		MBF._V.iUploadChunkNow++;
	} else {
		eA = APP.dg(MBF._V.sUploadId);
//		eA.innerHTML = eA.innerHTML1;
		if (MBF._V.iUploadChunksTotal == -1){
			APP.ajax({
				job: "upload-cancel",
				part: MBF._V.sUploadIdPart,
				filename: MBF._V.sUploadFileName,
				ext: MBF._V.sUploadFileExt,
			}, MBF.uploadcancel);
		} else {
			sF = MBF._V.sFolderUpload;
//			APP._V.oAjax.eSpinnerParent = APP.dg("accwaitspinupload");
			APP.ajax({
				job: "upload-done",
				filename: MBF._V.sUploadFileName,
				ext: MBF._V.sUploadFileExt,
				folder: sF,
				getp: MBF._V.sUploadGet,
				orig: APP.base64(MBF._V.sUploadOriginalFileName),
				number: MBF._V.iFileNow,
				id: MBF._V.sId,
			}, MBF.uploaddone);
		}
	}
},



uploadstart:function(oEvent){
	var eA, eB, oReader, sR, oOF, aF, sI, iD, eP, iI;
	eA = oEvent.target;
	if (eA.innerHTML == "Cancel"){
		MBF._V.iUploadChunksTotal = -1;
	} else {
		aF = eA.id.split("_");
		switch (aF[2]){

			case "statement":
				sR = /^([a-zA-Z0-9\s_\\.\-:.,])+(.pdf)$/;
				MBF._V.sUploadGet = "&step=1&cat=statement";
				MBF._V.sFolderUpload = "statement";
			break;

			case "logo":
				sR = /^([a-zA-Z0-9\s_\\.\-:.,])+(.jpg|.png|.jpeg)$/;
				MBF._V.sUploadGet = "&step=2&cat=logo";
				MBF._V.sFolderUpload = "logo";
			break;

		}
		eB = APP.dg("acc_fileupload_" + aF[2]);

//		for (iI = 0; iI < eB.files.length; iI++){

			oOF = eB.files[MBF._V.iFileNow];
			if ((!oOF) || (!oOF.name)){
				alert("You have to select a file.")
				return;
			}
			MBF._V.sUploadOriginalFileName = oOF.name;
			MBF._V.sUploadId = eA.id;
			MBF._V.sUploadIdPart = aF[2];
			sI = eB.value.toLowerCase();
			if (sR.test(sI)){
				if (typeof(FileReader) != "undefined"){
					eP = APP.dg("acc_uploadprogressbar_" + MBF._V.sUploadIdPart) ;
					eP.parentNode.style.opacity = 1;
					eP.style.width = "3%";
					oReader = new FileReader();
					eA.innerHTML1 = eA.innerHTML;
					eA.innerHTML = "Cancel";
					eA = APP.dg("acc_uploadprogress_" + MBF._V.sUploadIdPart);
					eA.style.opacity = 1;
					if (oReader.readAsArrayBuffer){
						oReader.onload = function(oEvent){
							MBF._V.oUploadData = oEvent.target.result;
							MBF._V.iUploadChunkNow = 0;
							MBF._V.iUploadChunksTotal = 
								1 + MBF._V.oUploadData.byteLength 
								/ MBF._V.iUploadChunkSize;
								iD = Date.now();
							MBF._V.sUploadFileName = 
								(iD + "-"
								+ APP.base64(MBF._V.sUploadOriginalFileName) 
								+ "-" + Math.random().toString(36).slice(-10))
								.replace(/=/g, "");
							MBF.uploadprogress();
						};
						oReader.readAsArrayBuffer(eB.files[MBF._V.iFileNow]);
					} else {
						oReader.onload = function(oEvent){
							var sData, aBytes, iI;
							sData = "";
							aBytes = new Uint8Array(oEvent.target.result);
							for (iI = 0; iI < aBytes.byteLength; iI++){
								sData += String.fromCharCode(aBytes[iI]);
							}
							MBF.uploadprogress(sData);
						};
						oReader.readAsArrayBuffer(eB.files[MBF._V.iFileNow]);
					}
				} else {
					alert("Unsupported browser");
				}
			} else {
				alert("Please upload a file with a valid extension. " + 
					"Also consider renaming the filename to be relatively simple " + 
					"regarding special characters.");
			}


//		}
	}
},



};



var OCR = {

V: {


	sApiUrl: "http://api.ocr.ubu/v1/",
	sApiKey: "1234567890",

	oApiHeader : {},
	iStartTime: 0,
	iPingInterval: 0,
	iPingTime: 1000,
	iRowEdit: 0,
	sFolderId: "",
	eFolder: {},
	eButton: 0,
	sPingUrl: "",
	oResult: {},
	aFiles: [],
	bThumbsDisplayed: 0,
},



cancel: async function(oEvent){
	var sUrl, eB;
	eB = oEvent.target;
	sUrl = OCR.V.sApiUrl + "cancel/" + oResult.result.id;
	console.log(sUrl);
	oResponse = await fetch(sUrl, OCR.V.oApiHeader);
	if (oResponse.ok){
		oResult = await oResponse.json();
		eB.innerHTML = "Process";
		console.log(oResult);
	}
},



display: function(){
	var aGrid, aEdit, sPR, aAnomalies, aRecon, aBalances, aSalaries, aIncomes, eA, eG, eT, eR, eD, iI, iJ, sBorder, sB, aColsTransactions, aColsBalances;
	eA = document.querySelector("#uploadbox");
	eA.style.display = "block";
	/*
	aColsTransactions = "P_R|10%, Date|12%, Description|24%, Amt In|9%|a, Amt Out|9%|a, Balance|9%|a, OCR In|9%|a, OCR Out|9%|a, OCR Bal|9%|a".split(", ");
	aColsBalances = "Balance|20%, Date|20%, Amount In|20%|a".split(", ");
	*/
	aColsTransactions = OCR.V.oResult.display.transactions;
	aColsBalances = OCR.V.oResult.display.balances;
	sBorder = "border border-greylight px-1 py-2";
	aGrid = OCR.V.oResult.grid;
	aEdit = OCR.V.oResult.edit;
	if (!aEdit){
		aEdit = [];
	}
	aBalances = OCR.V.oResult.balances;
	if (!aBalances){
		aBalances = [];
	}
	aSalaries = OCR.V.oResult.salaries;
	if (!aSalaries){
		aSalaries = [];
	}
	aIncomes = OCR.V.oResult.incomes;
	if (!aIncomes){
		aIncomes = [];
	}
	aRecon = OCR.V.oResult.recon;
	if (!aRecon){
		aRecon = [];
	}
	aAnomalies = OCR.V.oResult.anomalies;
//	eG = document.querySelector("#processing_section");
//	eG.style.height = "42px";
	eG = document.querySelector("#grid_header");
	eG.innerHTML = "";
//	eG.parentNode.style.height = "42px";
	eT = APP.ele(eG, "", "table");
	OCR.V.oResult.template.header_fields.forEach(function(sField){
		eR = APP.ele(eT, "", "tr");
		eD = APP.ele(eR, sBorder, "td");
		eD.innerHTML = APP.uppercasefirst(sField);
		eD = APP.ele(eR, sBorder, "td");
		eD.innerHTML = OCR.V.oResult.header[sField];
	});

	eG = document.querySelector("#grid_anomalies");
	eG.innerHTML = "";
//	eG.parentNode.style.height = "42px";
	eT = APP.ele(eG, "", "table");
	eT.border = 1;
	aAnomalies.forEach(function(aRow){
		eR = APP.ele(eT, "", "tr");
		aRow.forEach(function(sCol){
			eD = APP.ele(eR, sBorder, "td");
			eD.innerHTML = sCol;
		});
	});
	eG = document.querySelector("#grid_recon");
	eG.innerHTML = "";
//	eG.parentNode.style.height = "42px";
	eT = APP.ele(eG, "", "table");
	eT.border = 1;
	aRecon.forEach(function(aRow){
		eR = APP.ele(eT, "", "tr");
		aRow.forEach(function(sCol){
			eD = APP.ele(eR, sBorder, "td");
			eD.innerHTML = sCol;
		});
	});

	eG = document.querySelector("#grid_data");
	eG.innerHTML = "";
	eT = APP.ele(eG, "", "table");
	eR = APP.ele(eT, "", "tr");
	iI = 0;
	aColsTransactions.forEach(function(oC){
		eD = APP.ele(eR, sBorder, "th");
		eD.width = oC.width + "%";
		eD.innerHTML = oC.description;
		iI++;
	});


	
	iI = 0;
	aGrid.forEach(function(aRow){
		if (iI % 2){
			sB = ""
		} else {
			sB = "bg-greylight";
		}
		eR = APP.ele(eT, sB, "tr");
		eR.onclick=OCR.rowedit;
		eR.id = "row_" + iI;
		var sR = aRow[0];
		var sO = OCR.V.oResult.grid[iI][0];
		if (sR != sO){
			console.log(sR + " != " + sO);
		}
		if ((aEdit[iI]) && (aEdit[iI][0])){
				sPR = aRow[0];
				aRow = aEdit[iI];
				aRow[0] = sPR;
				// Do not display if the edited amounts agree.
				if (aRow[4] == aEdit[iI][7]){
					aRow[7] = "";
				}
				if (aRow[5] == aEdit[iI][8]){
					aRow[8] = "";
				}
				if (aRow[6] == aEdit[iI][9]){
					aRow[9] = "";
				}
		}
		aRow.forEach(function(sCol){
			iJ = 0;
			oC = aColsTransactions[iJ];//.split("|");
			if (oC.is_amount){
				sB = sBorder + " text-right";
			} else {
				sB = sBorder;
			}
			eD = APP.ele(eR, sB, "td");
			eD.innerHTML = sCol;
			iJ++;
		});
		iI++;
	});

	eG = document.querySelector("#grid_balances");
	eG.innerHTML = "";
	eT = APP.ele(eG, "my-4", "table");
	eR = APP.ele(eT, "", "tr");
	sB = "p-4";
	eD = APP.ele(eR, sB, "td");
	eD.innerHTML = aBalances[0][0];
	eD = APP.ele(eR, sB, "td");
	eD.innerHTML = aBalances[0][1];
	eD = APP.ele(eR, sB, "td");
	eD.innerHTML = aBalances[0][2];
	eD = APP.ele(eR, sB, "td");
	eD.innerHTML = aBalances[0][3];

	eT = APP.ele(eG, "", "table");
	eR = APP.ele(eT, "", "tr");
	iI = 0;
	aColsBalances.forEach(function(oC){
		eD = APP.ele(eR, sBorder, "th");
		eD.width = oC.width + "%";
		eD.innerHTML = oC.description;
		iI++;
	});
	iI = 0;
	aBalances.forEach(function(aRow){
		if (iI){
			eR = APP.ele(eT, "", "tr");
			iJ = 0;
			aRow.forEach(function(sCol){
				oC = aColsBalances[iJ];//.split("|");
				if (oC.is_amount){
					sB = sBorder + " text-right";
				} else {
					sB = sBorder;
				}
				
				eD = APP.ele(eR, sB, "td");
				eD.innerHTML = sCol;
				iJ++;
			});
		}
		iI++;
	});

	eG = document.querySelector("#grid_salaries");
	eG.innerHTML = "";
//	eG.parentNode.style.height = "42px";
	eT = APP.ele(eG, "my-4", "table");
	eR = APP.ele(eT, "", "tr");
	eD = APP.ele(eR, "p-4", "td");
	eD.innerHTML = "<u>Salaries</u>";
	aSalaries.forEach(function(aR){
		eR = APP.ele(eT, "", "tr");
		eD = APP.ele(eR, "p-4", "td");
		eD.innerHTML = aR[0];
		eD = APP.ele(eR, "p-4", "td");
		eD.innerHTML = aR[1];
		eD = APP.ele(eR, "p-4", "td");
		eD.innerHTML = aR[2];
		eD = APP.ele(eR, "p-4", "td");
		eD.innerHTML = aR[3];
	});

	eG = document.querySelector("#grid_salaries");
	eT = APP.ele(eG, "my-4", "table");
	eR = APP.ele(eT, "", "tr");
	eD = APP.ele(eR, "p-4", "td");
	eD.innerHTML = "<u>Other Income</u>";
	aIncomes.forEach(function(aR){
		eR = APP.ele(eT, "", "tr");
		eD = APP.ele(eR, "p-4", "td");
		eD.innerHTML = aR[0];
		eD = APP.ele(eR, "p-4", "td");
		eD.innerHTML = aR[1];
		eD = APP.ele(eR, "p-4", "td");
		eD.innerHTML = aR[2];
		eD = APP.ele(eR, "p-4", "td");
		eD.innerHTML = aR[3];
	});
},



edit_cancel: function(){
	console.log("cancel");
	APP.dg("transaction_edit").style.display = "none";
},



edit_save: async function(){
	var oResponse, oResult, sUrl, sRow;
	sRow = [
		OCR.V.iRowEdit,
		APP.dg("edit_date").value,
		"",
		APP.dg("edit_description").value,
		APP.dg("edit_amount_in").value,
		APP.dg("edit_amount_out").value,
		APP.dg("edit_balance").value,
		APP.dg("edit_ocr_in").value,
		APP.dg("edit_ocr_out").value,
		APP.dg("edit_ocr_balance").value,
	];
console.log(sRow);

	sRow = btoa(JSON.stringify(sRow)).replace(/[/]/g, "-").replace(/=/g, "_");
console.log(sRow);
	OCR.V.sFolderId = APP.dg("folder").value;
	OCR.V.oApiHeader = new Headers();
	OCR.V.oApiHeader.append("Authorization", "X-API-KEY " + OCR.V.sApiKey);
	OCR.V.oApiHeader = {
		method: "GET",
		withCredentials: true,
		credentials: "include",
		headers: OCR.V.oApiHeader,
	};
	sUrl = OCR.V.sApiUrl + 'row-edit/' + OCR.V.sFolderId + "/" + sRow;
	OCR.job(sUrl, 1);
	APP.dg("transaction_edit").style.display = "none";
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



job: async function(sUrl, bEditSave){
	var oResponse, oResult;
	if (!bEditSave){
		OCR.V.eButton.innerHTML = "Cancel";
	}
	OCR.V.oApiHeader = new Headers();
	OCR.V.oApiHeader.append("Authorization", "X-API-KEY " + OCR.V.sApiKey);
	OCR.V.oApiHeader = {
		method: "GET",
		withCredentials: true,
		credentials: "include",
		headers: OCR.V.oApiHeader,
	};
	oResponse = await fetch(sUrl, OCR.V.oApiHeader);
	if (oResponse.ok){
		oResult = await oResponse.json();
		console.log(oResult);
		window.setTimeout(function(){
			if (!bEditSave){
				OCR.V.eButton.innerHTML = "Process";
				window.clearInterval(OCR.V.iPingInterval);
			}
			if (!oResult.error){
				OCR.V.oResult = oResult.result;
				OCR.display();
			}
		}, 1000);
	}
},



load: function(){
	var sId;
	sId = APP.dg("folder").value;
	if (sId){
		APP.ajax({
			job: "statement-load",
			id: sId,
		}, function(oData){
			console.log(oData);
			OCR.V.oResult = oData.result;
//			APP.dg("processingbox").style.display = "block";
			OCR.display();
			eA = document.querySelector("#preview");
			eA.innerHTML = "";
			oData.result.thumbs.forEach(function(aT){
				eB = APP.ele(eA, "w-32 h-40 float-left border border-greydark p-1 mr-4 mb-1 cursor-pointer");
				eB.id = "thumb_" + aT[0] + "_" + aT[1];
				eB.onclick = OCR.pageload;
				eC = APP.ele(eB, "w-full h-full", "img");
				OCR.thumbnail(oData, eC, aT);
				eC = APP.ele(eB);
				eC.innerHTML = (aT[0] + 1) + " - " + (aT[1] + 1);
			});
			OCR.V.bThumbsDisplayed = 1;
		});
	}
},



pageload: function(oEvent){
	var eA, aId;
	eA = oEvent.target;
	while (!eA.id){
		eA = eA.parentNode;
	}
	aId = eA.id.split("_");
	APP.ajax({
		job: "pageload",
		id: document.querySelector("#folder").value,
		pdf: aId[1],
		page: aId[2],
	}, function(oData){
		if (oData.url){
			window.open(oData.url, "_blank").focus();
		}
	})
	console.log(eA);
},



progress: async function(){
	var iTime, eLog, sTime, oResponse, sMessage, sPid, iPages, aPages, eA, eB, eC;
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
			if ((oResult.result) && (oResult.result.ocr_page)){
				sMessage += ", page " + oResult.result.ocr_page;
			}
		}
		if ((oResult.result) && (oResult.result.pages)){
			iPages = 0;
			aPages = JSON.parse(oResult.result.pages);
			aPages.forEach(function(iP){
				iPages += iP
			})
			sMessage += ", pages: " + iPages;
		}
		if ((oResult.result) && (oResult.result.bank)){
			sMessage += ", bank: " + oResult.result.bank;
		}
		sPid = "PID: " + oResult.pid;
		eLog.innerHTML = sTime + " seconds<br>" + sMessage + "<br>" + sPid;

		if ((oResult.result) && (oResult.result.thumbs) && (!OCR.V.bThumbsDisplayed)){
			eA = document.querySelector("#preview");
			oResult.result.thumbs.forEach(function(aT){
				eB = APP.ele(eA, "w-32 h-40 float-left border border-greydark p-1 mr-4 mb-1 cursor-pointer");
				eB.id = "thumb_" + aT[0] + "_" + aT[1];
				eB.onclick = OCR.pageload;
				eC = APP.ele(eB, "w-full h-full", "img");
				OCR.thumbnail(oResult, eC, aT);
				eC = APP.ele(eB);
				eC.innerHTML = (aT[0] + 1) + " - " + (aT[1] + 1);
			});
			OCR.V.bThumbsDisplayed = 1;
		}
	}
},



tabpage: function(oEvent){
	var eA, aS, sBg, aP, iI;
	eA = oEvent.target;
	while (!eA.id){
		eA = eA.parentNode;
	}
	aS = eA.id.split("_");
	aS = [aS[1], parseInt(aS[1])];
	sBg = APP._V.oTheme.theme_color_primary;
	aP = APP.gcn("tabpage", 1);
	iI = 1;
	aP.forEach(function(eP){
		eA = APP.dg("tabbutton_" + iI);
		if (eP.id.split("_")[1] == aS[0]){
			eP.style.display = "block";
			eA.className = eA.className.replace("bg-greylight text-dark", "bg-blue text-light");
			if (sBg){
				eA.style.background = sBg;
			}
		} else {
			eP.style.display = "none";
			eA.className = eA.className.replace("bg-blue text-light", "bg-greylight text-dark");
			if (sBg){
				eA.style.background = "";
			}
		}
		iI++;
	});
},



thumbnail: async function(oData, eImg, aT){
	var sUrl, sId;
	sId = document.querySelector("#folder").value;
	sUrl = OCR.V.sApiUrl + "thumb/" + sId + "/" + aT[0] + "/" + (aT[1] + 1);
	oResponse = await fetch(sUrl, OCR.V.oApiHeader);
	if (oResponse.ok){
		oResult = await oResponse.json();
		eImg.src = "data:image/gif;base64," + oResult.result.b64;
	}
},



rowedit: function(oEvent){
	var eA, iY, iX, iRow, aRow;
	eA = oEvent.target;
	while ((eA) && (eA.tagName != "TR")){
		eA = eA.parentNode;
	}
	if (eA){
		iRow = parseInt(eA.id.split("_")[1]);
	}
	OCR.V.iRowEdit = iRow;
	iY = window.scrollY;
	iX = parseInt((window.innerWidth - 400) / 2);
	eA = APP.dg("transaction_edit");
	eA.style.top = (iY + 100) + "px";
	eA.style.left = (iX) + "px";
	eA.style.display = "block";
	aRow = OCR.V.oResult.grid[iRow];
	console.log(OCR.V.oResult.grid[iRow]);
	APP.dg("edit_date").value = aRow[1];
	APP.dg("edit_description").value = aRow[3];
	APP.dg("edit_amount_in").value = aRow[4];
	APP.dg("edit_amount_out").value = aRow[5];
	APP.dg("edit_balance").value = aRow[6];
	APP.dg("edit_ocr_in").value = aRow[7];
	APP.dg("edit_ocr_out").value = aRow[8];
	APP.dg("edit_ocr_balance").value = aRow[9];
},



section: function(oEvent){
	var eA, bF, aA;
	eA = oEvent.target.parentNode;
	bF = 0;
	while ((!bF) && (eA)){
		if (eA.id){
			aA = eA.id.split("_");
			if ((aA[1]) && (aA[1] == "section")){
				bF = 1;
			}
		}
		if (!bF){
			eA = eA.parentNode;
		}
	}
	if (bF){
		if (eA.style.height != "42px"){
			eA.style.height = "42px";
		} else {
			eA.style.height = "";
		}
	}
},



start: async function(aFiles){
	var sButton, sUrl, eA;
	OCR.V.aFiles = aFiles;
	sButton = OCR.V.eButton.innerHTML;
	if (sButton == "Process"){
		OCR.V.sFolderId = OCR.V.eFolder.value;
		OCR.V.sPingUrl = OCR.V.sApiUrl + "progress/" + OCR.V.sFolderId;
		OCR.V.iStartTime = new Date().getTime();
		OCR.V.iPingInterval = window.setInterval(OCR.progress, OCR.V.iPingTime);
		sUrl = OCR.V.sApiUrl + "job/" + OCR.V.sFolderId;
		OCR.job(sUrl);
	} else {
		OCR.V.eButton.innerHTML = "Process";
		sUrl = OCR.V.sApiUrl + "cancel/" + OCR.V.sFolderId;
		eA = document.querySelector("#uploadbox");
		eA.style.display = "none";
		window.clearInterval(OCR.V.iPingInterval);
	}
},



};



	

var ROL = {

	V: {
	aMemberOf: [],
	aNotMemberOf: [],
},



init: function(){
	var aM, aN, eA, eB, iI, eO, iSM, iSN;
	aM = [];
	aN = [];
	eA = APP.dg("selmember");
	for (iI = 0; iI < eA.options.length; iI++){
		eO = eA.options[iI];
		if (eO.selected){
			iSM = iI + 1;
		}
		aM.push([eO.value, eO.textContent]);
	}

	eB = APP.dg("selnotmember");
	for (iI = 0; iI < eB.options.length; iI++){
		eO = eB.options[iI];
		if (eO.selected){
			iSN = iI + 1;
		}
		aN.push([eO.value, eO.textContent]);
	}
	ROL.V.aMemberOf = aM;
	ROL.V.aNotMemberOf = aN;
	APP.dg("memberof").value = JSON.stringify(aM);
	APP.dg("notmemberof").value = JSON.stringify(aN);

},



member: function(bMember){
	var aM, aN, eA, eB, eO, eP, iI, iSM, iSN;
	iSM = 0;
	iSN = 0;
	aM = [];
	aN = [];
	eA = APP.dg("selmember");
	for (iI = 0; iI < eA.options.length; iI++){
		eO = eA.options[iI];
		if (eO.selected){
			iSM = iI + 1;
		}
		aM.push([eO.value, eO.textContent]);
	}

	eB = APP.dg("selnotmember");
	for (iI = 0; iI < eB.options.length; iI++){
		eO = eB.options[iI];
		if (eO.selected){
			iSN = iI + 1;
		}
		aN.push([eO.value, eO.textContent]);
	}
	if (bMember){
		if (iSN){
			eO = eB.options[iSN - 1];
			eP = APP.ele(eA, "", "option");
			eP.value = eO.value;
			eP.textContent = eO.textContent;
			eO.parentNode.removeChild(eO);
		}
	} else {
		if (iSM){
			eO = eA.options[iSM - 1];
			eP = APP.ele(eB, "", "option");
			eP.value = eO.value;
			eP.textContent = eO.textContent;
			eO.parentNode.removeChild(eO);
		}
	}
	ROL.init();
},


};

