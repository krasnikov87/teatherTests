teacherTest.combo.Search = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    teacherTest.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
    });
    this.addEvents('clear', 'search');
};
Ext.extend(teacherTest.combo.Search, Ext.form.TwinTriggerField, {

    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        };
    },

    _triggerSearch: function () {
        this.fireEvent('search', this);
    },

    _triggerClear: function () {
        this.fireEvent('clear', this);
    },

});
Ext.reg('teachertest-combo-search', teacherTest.combo.Search);
Ext.reg('teachertest-field-search', teacherTest.combo.Search);


teacherTest.combo.questionType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        xtype:'combobox',
        hiddenName: config.name,
        store: new Ext.data.ArrayStore({

            id: 0
            ,fields: ['value','display']
            ,data: [
                ['radio', _('teachertest-combo-question-types-radio')],
                ['checkbox', _('teachertest-combo-question-types_checkbox')],

            ]
        })
        ,mode: 'local'
        ,displayField: 'display'
        ,valueField: 'value'
    });
    teacherTest.combo.questionType.superclass.constructor.call(this,config);
};
Ext.extend(teacherTest.combo.questionType,MODx.combo.ComboBox);
Ext.reg('teachertest-combo-question-types',teacherTest.combo.questionType);