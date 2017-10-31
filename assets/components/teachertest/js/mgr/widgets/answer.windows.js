teacherTest.window.CreateAnswer = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'teachertest-answer-window-create';
    }
    Ext.applyIf(config, {
        title: _('teachertest_answer_name'),
        modal:true,
        width: 670,
        autoHeight: true,
        url: teacherTest.config.connector_url,
        action: 'mgr/answer/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }],

    });
    teacherTest.window.CreateAnswer.superclass.constructor.call(this, config);
    this.on('activate', function() {
        if (MODx.loadRTE) {
            MODx.loadRTE(config.id+'-answer');
        }
    });
};
Ext.extend(teacherTest.window.CreateAnswer, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'question_id',
            id: config.id + '-question_id',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('teachertest_answer_min'),
            name: 'answer',
            id: config.id + '-answer',
            anchor: '99%'
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('teachertest_answer_correct'),
            name: 'correct',
            id: config.id + '-correct',
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('teachertest-answer-window-create', teacherTest.window.CreateAnswer);


teacherTest.window.UpdateAnswer = function (config) {
    config = config || {};

    if (!config.id) {
        config.id = 'teachertest-answer-window-update';
    }

    Ext.applyIf(config, {
        title: _('teachertest_answer_name'),
        width: 700,
        autoHeight: true,
        url: teacherTest.config.connector_url,
        action: 'mgr/answer/update',
        fields: this.getFields(config),
        modal:true,
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    teacherTest.window.UpdateAnswer.superclass.constructor.call(this, config);
    this.on('activate', function() {
        if (MODx.loadRTE) {
            MODx.loadRTE(config.id+'-answer');
        }
    });
};
Ext.extend(teacherTest.window.UpdateAnswer, MODx.Window, {

    getFields: function (config) {
        return [ {
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'hidden',
            name: 'question_id',
            id: config.id + '-question_id',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('teachertest_answer_min'),
            name: 'answer',
            id: config.id + '-answer',
            anchor: '99%'
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('teachertest_answer_correct'),
            name: 'correct',
            id: config.id + '-correct',
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('teachertest-answer-window-update', teacherTest.window.UpdateAnswer);