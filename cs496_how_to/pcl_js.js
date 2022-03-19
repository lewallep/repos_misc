function goHome() {
	window.location = "index.html";
}

function goStepOne() {
	window.location = "stepOne.html";
}

function goStepTwo() {
	window.location = "stepTwo.html";
}

function goStepThree() {
	window.location = "stepThree.html";
}

function goStepFour() {
	window.location = "stepFour.html";
}

function goStepFive() {
	window.location = "stepFive.html";
}

function goStepSix() {
	window.location = "stepSix.html";
}

function goStepSeven() {
	window.location = "stepSeven.html";
}

function goSummary() {
	window.location = "summary.html";
}

function GetMap() {   
	var map = new Microsoft.Maps.Map(document.getElementById("mapDiv"), 
                   {credentials: "Aj5SNlO1-48Vv3sIZuahCSTqyjg0u9rKB5xJYAcA8n9FmjQUfCxvEuxkNio-KRGY",
                    center: new Microsoft.Maps.Location(44.568832, -123.279153),
                    mapTypeId: Microsoft.Maps.MapTypeId.road,
                    zoom: 16});
}

function testStuff()
{
	var par;
	var parText;

	par = document.createElement("p");
	parText = document.createTextNode("Look you have pushed this button.");
	par.appendChild(parText);
	document.getElementById("mainP").appendChild(par);
}