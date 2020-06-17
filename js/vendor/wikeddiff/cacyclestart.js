var WikEdDiffTool = {};
var WikEdDiff;
var wikEdDiffConfig;
var WED;


WikEdDiffTool.init = function() {

	// set debug shortcut
	if ( (WED === undefined) && (window.console !== undefined ) ) {
		WED = window.console.log;
	}
	
	wikEdDiffConfig = {
		"fullDiff":true,
		"showBlockMoves":false,
		"charDiff":false,
		"repeatedDiff":true,
		"recursiveDiff":true,
		"recursionMax":5,
		"unlinkBlocks":true,
		"blockMinLength":3,
		"unlinkMax":10,
		"coloredBlocks":false,
		"debug":false,
		"timer":false,
		"unitTesting":false,
		"noUnicodeSymbols":false,
		"stripTrailingNewline":false,
		"stylesheet": ''
	}

	wikEdDiffConfig.htmlCode = {
		'noChangeStart': '<div class="wikEdDiffNoChange">',
		'noChangeEnd': '</div>',
 
		'containerStart': '',
		'containerEnd': '',
 
		'fragmentStart': '',
		'fragmentEnd': '',
		'separator': '<div class="wikEdDiffSeparator"></div>',
 
		'insertStart': '<span class="newVersion">',
		'insertStartBlank': '<span class="newVersion newVersionBlank">',
		'insertEnd': '</span>',
 
		'deleteStart': '<span class="oldVersion">',
		'deleteStartBlank': '<span class="oldVersion oldVersionBlank" >',
		'deleteEnd': '</span>',
 
		'blockStart':
			'<span class="newVersion" >',
		'blockColoredStart':
			'<span class="newVersion" >',
		'blockEnd': '</span>',
 
		'markLeft':
			'<span class="wikEdDiffMarkLeft{nounicode}"></span>',
		'markLeftColored':
			'<span class="wikEdDiffMarkLeft{nounicode} wikEdDiffMark wikEdDiffMark{number}"></span>',
 
		'markRight':
			'<span class="wikEdDiffMarkRight{nounicode}"></span>',
		'markRightColored':
			'<span class="wikEdDiffMarkRight{nounicode} wikEdDiffMark wikEdDiffMark{number}"></span>',
 
		'newline': '\n',
		'tab': '\t',
		'space': ' ',
 
		'omittedChars': '<span class="wikEdDiffOmittedChars">…</span>',
 
		'errorStart': '<div class="wikEdDiffError" title="Error: diff not consistent with versions!">',
		'errorEnd': '</div>'
	};
 
	
	return;
};





//
// WikEdDiffTool.diff(): click handler for compare button, get options and text versions, call wikEdDiff.diff()
//   

WikEdDiffTool.diff = function(a,b) {
	var wikEdDiff = new WikEdDiff();
	var diffHtml = wikEdDiff.diff(a, b);
	return diffHtml;
};


// initialize WikEdDiffTool
WikEdDiffTool.init();