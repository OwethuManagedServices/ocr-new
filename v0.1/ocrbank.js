console.log(OCRsrv);

var MBA = {

	_V: {
		oAjax: {
			bDone: 0,
			bRaw: 0,
			eButtonMask: 0,
			eSpinnerParent: 0,
			iTimeInterval: 0,
		},	
	},



ajax: function(oData, fCallback){
	var sData, oX, oIn, eC, iT, iT1, iDec;
	iT = new Date().getTime();
	if (MBA._V.oAjax.eSpinnerParent){
		eC = MBA.dg("bk_spinner");
		eC.style.display = "block";
		if (MBA._V.oAjax.eButtonMask){
			MBA._V.oAjax.eButtonMask.style.display = "block";
		}
	}
	if (!oData.notime){
		MBA._V.oAjax.iTimeInterval = window.setInterval(function(){
			iT1 = new Date().getTime();
			iT1 = parseInt((iT1 - iT) / 100) / 10;
			iDec = (iT1 + "").indexOf(".");
			if (iDec == -1){
				iT1 += ".0";
			}
			MBA.dg("bk_time").innerHTML = iT1;
		}, 100);
	}
	oIn = oData;
	MBA._V.oAjax.bDone = 0;
	MBA._V.oAjax.bRaw = oData.raw;
	MBA.logtoscreen("Server process start <b>" + oData.job + "</b>", 1);
	oData = MBA.json(0, oData);
	oData = "action=" + OCRsrv.action + "&security=" + OCRsrv.nonce +
		"&data=" + MBA.base64(oData);
	oX = new XMLHttpRequest();
	oX.open("post", OCRsrv.ajaxurl);
	oX.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	try {
		oX.send(oData);
	} catch(error){
		if (MBA._V.oAjax.iTimeInterval){
			window.clearInterval(MBA._V.oAjax.iTimeInterval);
			MBA._V.oAjax.iTimeInterval = 0;
		}
		if (fCallback){
			fCallback({error:error});
		}
	}	
	oX.onreadystatechange = function (){
		if (oX.readyState == 4){
			if (MBA._V.oAjax.iTimeInterval){
				window.clearInterval(MBA._V.oAjax.iTimeInterval);
				MBA._V.oAjax.iTimeInterval = 0;
			}
			MBA._V.oAjax.bDone = 1;
			if (MBA._V.oAjax.eButtonMask){
				MBA._V.oAjax.eButtonMask.style.display = "none";
			}
			if (eC){
				eC.style.display = "none";
			}
			MBA._V.oAjax.eButtonMask = 0;
			MBA._V.oAjax.eSpinnerParent = 0;
			if (oX.status == 200){
				sData = oX.responseText;
				if (!MBA._V.oAjax.bRaw){
					sData = MBA.json(MBA.base64(0, sData));
				}
				if (fCallback){
					fCallback(sData);
				} else {
//					MBA.gcn("mainpage").innerHTML = sData.html;
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



amount: function(sValue){
	var iI, iL, sD;
	sValue += "";
	if (sValue =="0"){
		return [0, "", true]
	}
	sD = sValue;
	iL = sValue.length;
	iI = sValue.indexOf(".");
	if (iI == -1){
		sD = (parseFloat(sValue) / 100).toFixed(2);
	} else {
		if (iL - iI != 3){
			sD = (parseFloat(sValue) / 100).toFixed(2);
		}
	}
	return [parseFloat(sD), sValue, (sValue == sD)];
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



logtoscreen(sText, bNotShowDuration, sClass){
	var sTime, eA, sT, sD, sStyle;
	if (bNotShowDuration){
		sD = "<td></td>";
	} else {
		sD = "<td>" + MBA.dg("bk_time").innerHTML + "</td>";
	}
	eA = MBA.dg("bk_log");
	sT = eA.innerHTML;
	sTime = new Date();
	if (sClass){
		sStyle = " class='err_" + sClass + "'";
	} else {
		sStyle = "";
	}
	sTime = sTime.toISOString().split("T")[1].replace("Z", "");

	eA.innerHTML = sT + "<tr><td>" + sTime + "</td>" + sD + "<td" + sStyle + ">" + sText + "</td></tr>";
	eA.parentNode.scrollTop = eA.parentNode.scrollHeight;
},



};




var MBF = {

_V: {
	oUploadData: 0,
	iUploadChunkNow: 0,
	iUploadChunkSize: 1024 * 1024,
	iUploadChunksTotal: 0,
	sUploadFileName: "",
	sUploadFileExt: "",
	sUploadGet: "",
	sFolderUpload: "",
	sUploadOriginalFileName: "",
	iStep: 1,
	iStartTime : 0,
	sOcrFolder : "",
	sUploadId: "",
	sUploadIdPart: "",
	bDone: 0,
	
},



uploadprogress: function(){
	var oC, eA, sF, aR, iNT, uInt8Array, oRec, iFileSize;
	oC = MBF._V.oUploadData.slice
		(MBF._V.iUploadChunkNow * MBF._V.iUploadChunkSize, 
		(MBF._V.iUploadChunkNow + 1) * MBF._V.iUploadChunkSize);
	if (MBF._V.iUploadChunkNow < MBF._V.iUploadChunksTotal){
		oReq = new XMLHttpRequest();

		oReq.open("POST", OCRsrv.url + "upload.php" 
			+ "?file=" + MBF._V.sUploadFileName + MBF._V.sUploadGet, true);
		oReq.onload = function (oEvent) {
			aR = (oEvent.target.responseText).split("|");
			MBF._V.aUploadInfo = aR;
			iFileSize = aR[0];
			MBF._V.sUploadFileExt = aR[1];
			MBA.dg("bk_uploadprogressbar_" 
				+ MBF._V.sUploadIdPart).style.width = (iFileSize * 100
				/ MBF._V.oUploadData.byteLength) + "%";
			MBF.uploadprogress();
		};
		uInt8Array = new Uint8Array(oC);
		oReq.send(uInt8Array.buffer);
		MBF._V.iUploadChunkNow++;
	} else {
		if (MBF._V.iUploadChunksTotal == -1){
			MBA.ajax({
				job: "upload-cancel",
				part: MBF._V.sUploadIdPart,
				filename: MBF._V.sUploadFileName,
				ext: MBF._V.sUploadFileExt,
			}, MBF.uploadcancel);
		} else {
			sF = MBF._V.sFolderUpload;
			iNT = 1;
			if (sF == "statement"){
				iNT = 0;
			}
			MBA.logtoscreen("Upload success, converting pages to PNG", 1);
			MBA.ajax({
				job: "pdf-to-png",
				notime: iNT,
				filename: MBF._V.sUploadFileName,
				extension: MBF._V.sUploadFileExt,
				folder: sF,
				getp: MBF._V.sUploadGet,
				step: MBF._V.iStep,
				orig: (MBF._V.sUploadOriginalFileName),
			}, MBF.uploaddone);
		}
	}
},



uploadstart:function(oEvent){
	var eA, eB, oReader, sR, oOF, aF, sI, iD, eP, iT, iT1, iInt, sF;
	sF = MBA.dg("bk_folder").value;
	eA = MBA.dg("bk_log");
	eA.innerHTML = "";
	eA = oEvent.target;
	MBF._V.iStartTime = new Date().getTime();
	if (sF.length == 35){
		eA.innerHTML1 = eA.innerHTML;
		eA.innerHTML = "Cancel";
		MBF._V.sOcrFolder = sF;
		MBF._V.sUploadFileName = sF;
		MBF._V.sUploadId = eA.id;
		MBF.processfolder();
		return;
	}
	iInt = window.setInterval(function(){
		if (MBF._V.bDone){
			window.clearInterval(iInt);
			MBF._V.bDone = 0;
		}
		iT1 = new Date().getTime();
		iT1 = parseInt((iT1 - MBF._V.iStartTime) / 100) / 10;
		MBA.dg("bk_time").innerHTML = iT1;
	}, 20);
	if (eA.innerHTML == "Cancel"){
		MBF._V.iUploadChunksTotal = -1;
	} else {
		aF = eA.id.split("_");
		switch (aF[2]){

			case "statement":
				sR = /^([a-zA-Z0-9\s_\\.\-:.,])+(.pdf)$/;
				MBF._V.sFolderUpload = "statement";
				MBF._V.sUploadGet = "&step=1";
			break;

		}
		eB = MBA.dg("bk_fileupload_" + aF[2]);
		oOF = eB.files[0];
		if ((!oOF) || (!oOF.name)){
			alert("You have to select a file.")
			return;
		}
		MBF._V.sUploadOriginalFileName = oOF.name;
		MBF._V.sUploadId = eA.id;
		MBF._V.sUploadIdPart = aF[2];
		sI = eB.value.toLowerCase();
		if (sR.test(sI)){
			MBA.logtoscreen("Uploading <b>" + aF[2] + "</b>, filename <b>" + oOF.name + "</b>", 1);
			if (typeof(FileReader) != "undefined"){
				eP = MBA.dg("bk_uploadprogressbar_" + MBF._V.sUploadIdPart) ;
				eP.parentNode.style.opacity = 1;
				eP.style.width = "3%";
				oReader = new FileReader();
				eA.innerHTML1 = eA.innerHTML;
				eA.innerHTML = "Cancel";
				eA = MBA.dg("bk_uploadprogress_" + MBF._V.sUploadIdPart);
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
							+(MBA.base64(MBF._V.sUploadOriginalFileName) + "AAAAAAAAAA").substring(0, 10)
							+ "-" + Math.random().toString(36).slice(-10))
							.replace(/=/g, "");
						MBF.uploadprogress();
					};
					oReader.readAsArrayBuffer(eB.files[0]);
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
					oReader.readAsArrayBuffer(eB.files[0]);
				}
			} else {
				alert("Unsupported browser");
			}
		} else {
			alert("Please upload a file with a valid extension. " + 
				"Also consider renaming the filename to be relatively simple " + 
				"regarding special characters.");
		}
	}
},



};



