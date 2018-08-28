/*
 * jQuery Date Selector Plugin
 * 日期联动选择插件
 * 
 * Demo:
        $("#calendar").DateSelector({
                ctlYearId: <年控件id>,
                ctlMonthId: <月控件id>,
                ctlDayId: <日控件id>,
                defYear: <默认年>,
                defMonth: <默认月>,
                defDay: <默认日>,
                minYear: <最小年|默认为1882年>,
                maxYear: <最大年|默认为本年>
        });

   HTML:<div id="calendar"><SELECT id=idYear></SELECT>年 <SELECT id=idMonth></SELECT>月 <SELECT id=idDay></SELECT>日</div>
 */
(function ($) {
    //SELECT控件设置函数
    function setSelectControl(oSelect, iStart, iLength, iIndex, clear) {
    	if(clear){
    		oSelect.empty();
    	}
        for (var i = 0; i < iLength; i++) {
            if ((parseInt(iStart) + i) == iIndex)
                oSelect.append("<option selected='selected' value='" + (parseInt(iStart) + i) + "'>" + (parseInt(iStart) + i) + "</option>");
            else
                oSelect.append("<option value='" + (parseInt(iStart) + i) + "'>" + (parseInt(iStart) + i) + "</option>");
        }
    };

    $.fn.DateSelector = function (options) {
        options = options || {};

        //初始化
        this._options = {
            ctlYearId: null,
            ctlMonthId: null,
            ctlDayId: null,
            defYear: 0,
            defMonth: 0,
            defDay: 0,
            minYear: 1882,
			minMonth: 1,
			minDay: 0,
            maxYear: new Date().getFullYear(),
			maxMonth: 0,
			maxDay: 0,
			clear: true
        }

        for (var property in options) {
            this._options[property] = options[property];
        }

        this.yearValueId = $("#" + this._options.ctlYearId);
        this.monthValueId = $("#" + this._options.ctlMonthId);
        this.dayValueId = $("#" + this._options.ctlDayId);

        var dt = new Date(),
        iMonth = parseInt(this.monthValueId.attr("data") || this._options.defMonth),
        iDay = parseInt(this.dayValueId.attr("data") || this._options.defDay),
        iMinYear = parseInt(this._options.minYear),
        iMaxYear = parseInt(this._options.maxYear),
		iMinMonth = parseInt(this._options.minMonth);
				
        this.Year = parseInt(this.yearValueId.attr("data") || this._options.defYear) || dt.getFullYear();
        this.Month = 1 <= iMonth && iMonth <= 12 ? iMonth : dt.getMonth() + 1;
		//add by zcc
		this.minMonth = iMinMonth && iMinMonth < this.Month ? iMinMonth: this.Month;
		this.clear = this._options.clear;
		//end by zcc
        this.Day = iDay > 0 ? iDay : dt.getDate();
        this.minYear = iMinYear && iMinYear < this.Year ? iMinYear : this.Year;
        this.maxYear = iMaxYear && iMaxYear > this.Year ? iMaxYear : this.Year;

        //初始化控件
        //设置年
        setSelectControl(this.yearValueId, this.minYear, this.maxYear - this.minYear + 1, this.Year, this.clear);
        //设置月
        //setSelectControl(this.monthValueId, 1, 12, this.Month);
		//modify by zcc 设置最小显示的月份
		  setSelectControl(this.monthValueId, this.minMonth, 12, this.Month, this.clear);
		//end modify
        //设置日
        var daysInMonth = new Date(this.Year, this.Month, 0).getDate(); //获取指定年月的当月天数[new Date(year, month, 0).getDate()]
        if (this.Day > daysInMonth) { this.Day = daysInMonth; };
        //setSelectControl(this.dayValueId, 1, daysInMonth, this.Day);
		setSelectControl(this.dayValueId, 0, daysInMonth+1, this.Day, this.clear);

        var oThis = this;
        //绑定控件事件
        this.yearValueId.change(function () {
            oThis.Year = $(this).val();
            setSelectControl(oThis.monthValueId, 1, 12, oThis.Month, oThis.clear);
            oThis.monthValueId.change();
        });
        this.monthValueId.change(function () {
            oThis.Month = $(this).val();
            var daysInMonth = new Date(oThis.Year, oThis.Month, 0).getDate();
            if (oThis.Day > daysInMonth) { oThis.Day = daysInMonth; };
            //setSelectControl(oThis.dayValueId, 1, daysInMonth, oThis.Day);
			setSelectControl(oThis.dayValueId, 0, daysInMonth+1, oThis.Day, oThis.clear);
        });
        this.dayValueId.change(function () {
            oThis.Day = $(this).val();
        });
    };
})(jQuery);