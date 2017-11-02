teacherTest.window.CreateQuestions = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'teachertest-question-window-create';
    }
    Ext.applyIf(config, {
        title: _('teachertest_question_name'),
        width: 670,
        autoHeight: true,
        modal:true,
        url: teacherTest.config.connector_url,
        action: 'mgr/question/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }],
        success: function (v1, v2, v3) {
            console.log(v2)
            var res = JSON.parse(v2.response.responseText);

            MODx.Ajax.request({
                url: this.config.url,
                params: {
                    action: 'mgr/question/get',
                    id: res.object.id
                },
                listeners: {
                    success: {
                        fn: function (r) {
                            var w = Ext.getCmp('teachertest-question-window-update');
                            if(w){
                                w.close();
                            }
                            w = MODx.load({
                                xtype: 'teachertest-question-window-update',
                                id: 'teachertest-question-window-update',
                                record: r,
                                listeners: {
                                    success: {
                                        fn: function () {
                                            Ext.getCmp('teachertest-grid-items').refresh();
                                        }, scope: this
                                    }
                                }
                            });
                            w.reset();
                            w.setValues(r.object);
                            w.show(Ext.getBody());
                        }, scope: this
                    }
                }
            });
        }
    });
    teacherTest.window.CreateQuestions.superclass.constructor.call(this, config);
    this.on('activate', function() {
        if (MODx.loadRTE) {
            MODx.loadRTE(config.id+'-description');
        }
    });
};
Ext.extend(teacherTest.window.CreateQuestions, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'test_id',
            id: config.id + '-test-id',
            anchor: '99%',
            allowBlank: false,
            value: config.value
        }, {
            xtype: 'textarea',
            fieldLabel: _('teachertest_question_name'),
            name: 'question',
            id: config.id + '-description',
            height: 150,
            anchor: '99%'
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('teachertest-question-window-create', teacherTest.window.CreateQuestions);


teacherTest.window.UpdateQuestions = function (config) {
    config = config || {};

    if (!config.id) {
        config.id = 'teachertest-question-window-update';
    }

    Ext.applyIf(config, {
        title: _('teachertest_question_name'),
        width: 700,
        autoHeight: true,
        url: teacherTest.config.connector_url,
        action: 'mgr/question/update',
        fields: this.getFields(config),
        modal:true,
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    teacherTest.window.UpdateQuestions.superclass.constructor.call(this, config);
    this.on('activate', function() {
        if (MODx.loadRTE) {
            MODx.loadRTE(config.id+'-description');
        }
    });
};
Ext.extend(teacherTest.window.UpdateQuestions, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('teachertest_question_name'),
                layout: 'form',
                items: [{
                    xtype: 'hidden',
                    name: 'id',
                    id: 'question-id',
                    anchor: '99%',
                    allowBlank: false,
                }, {
                    xtype: 'hidden',
                    name: 'test_id',
                    id: config.id + '-test-id',
                    anchor: '99%',
                    allowBlank: false,
                    value: config.value
                }, {
                    xtype: 'textarea',
                    fieldLabel: _('teachertest_question_name'),
                    name: 'question',
                    id: config.id + '-description',
                    height: 150,
                    anchor: '99%'
                }, {
                    xtype: 'xcheckbox',
                    boxLabel: _('teachertest_item_active'),
                    name: 'status',
                    id: config.id + '-active',
                    checked: true,
                }, {
                    xtype: 'teachertest-combo-question-types',
                    fieldLabel: _('teachertest_question_type'),
                    name: 'type',
                    id: config.id + '-type',
                    value: 'radio'
                }],
            }, {
                title: _('teachertest_answer_names'),
                layout: 'anchor',
                items: [{
                    xtype: 'teachertest-grid-answer',
                    fieldLabel: _('teachertest_answer_names'),
                    record: config.record.object
                }]
            }],
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('teachertest-question-window-update', teacherTest.window.UpdateQuestions);