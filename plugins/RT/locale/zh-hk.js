var RTTranslate=function(key){
	var T={
		"First page":"最前一頁",
		"Previous page":"上一頁",
		"Next page":"下一頁",
		"Last page":"最後一頁",
		"Refresh":"重新載入",
		"Increase font size":"增加字體大小",
		"Decrease font size":"減少字體大小"
	};
	var s= T[key];
	if(s===undefined){
		return key;
	}
	return s;
};