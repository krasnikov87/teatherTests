teacherTest.window.CreateItem = function (config) {
    config = config || {};
    if(!config.id) {
        config.id = 'teachertest-item-window-create';
    }
    Ext.applyIf(config, {
        title: _('teachertest_item'),
        width: 720,
        modal:true,
        autoHeight: false,
        height:700,
        url: teacherTest.config.connector_url,
        action: 'mgr/tests/create',
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
                    action: 'mgr/tests/get',
                    id: res.object.id
                },
                listeners: {
                    success: {
                        fn: function (r) {
                            var w = Ext.getCmp('teachertest-item-window-update');
                            if(w){
                                w.close();
                            }
                            w = MODx.load({
                                xtype: 'teachertest-item-window-update',
                                id: 'teachertest-item-window-update',
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
    teacherTest.window.CreateItem.superclass.constructor.call(this, config);
    this.on('activate', function() {
        if (MODx.loadRTE) {
            MODx.loadRTE(config.id+'-description');
        }
    });
};
Ext.extend(teacherTest.window.CreateItem, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'textfield',
            fieldLabel: _('teachertest_item_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('teachertest_item_description'),
            name: 'description',
            id: config.id + '-description',
            height: 150,
            anchor: '99%'
        }, {
            xtype: 'modx-combo-browser',
            fieldLabel: _('teachertest_item_image'),
            name: 'image',
            id: config.id +'-image',
            openTo: 'assets/images/',
            anchor: '99%',
            readonly: true,
            listeners: {
                'select': function () {
                    Ext.getCmp('photoHtml').update('<img src="/'+this.value+'" style="width: 100%; max-width: 300px; height: auto; max-height: 300px; margin-top:10px">');
                },
                'change': function (obj, newValue, oldValue, eOpts ) {
                    Ext.getCmp('photoHtml').update('<img src="/'+newValue+'" style="width: 100%; max-width: 300px; height: auto; max-height: 300px; margin-top:10px">');
                }
            }
        }, {
            id: 'photoHtml',
            hidetitle:true,
            html: '<img src="#">'
        }, {
            xtype: 'numberfield',
            fieldLabel: _('teachertest_item_count_questions'),
            name: 'count_questions',
            id: config.id + '-count_questions',
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('teachertest-item-window-create', teacherTest.window.CreateItem);


teacherTest.window.UpdateItem = function (config) {
    //console.log(config);
    config = config || {};
    if(!config.id) {
        config.id = 'teachertest-item-window-update';
    }
    Ext.applyIf(config, {
        title: _('teachertest_item'),
        width: 720,
        modal:true,
        autoHeight: false,
        height: 720,
        url: teacherTest.config.connector_url,
        action: 'mgr/tests/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    teacherTest.window.UpdateItem.superclass.constructor.call(this, config);
    this.on('activate', function() {
        if (MODx.loadRTE) {
            MODx.loadRTE(config.id+'-description');
        }
    });
};
Ext.extend(teacherTest.window.UpdateItem, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('teachertest_item'),
                layout: 'form',
                items: [{
                    xtype: 'hidden',
                    name: 'id',
                    id: 'item-id',
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('teachertest_item_name'),
                    name: 'name',
                    id: config.id + '-name',
                    anchor: '99%',
                    allowBlank: false,
                }, {
                    xtype: 'textarea',
                    fieldLabel: _('teachertest_item_description'),
                    name: 'description',
                    id: config.id + '-description',
                    anchor: '99%',
                    height: 150,
                }, {
                    xtype: 'modx-combo-browser',
                    fieldLabel: _('teachertest_item_image'),
                    name: 'image',
                    id: config.id + '-image',
                    openTo: 'assets/images/',
                    anchor: '99%',
                    listeners: {
                        'select': function () {
                            Ext.getCmp('photoHtml').update('<img src="/' + this.value + '" style="width: 100%; max-width: 300px; height: auto; max-height: 300px">');
                        },
                        'change': function (obj, newValue, oldValue, eOpts) {
                            Ext.getCmp('photoHtml').update('<img src="/' + newValue + '" style="width: 100%; max-width: 300px; height: auto; max-height: 300px; margin-top:10px">');
                        }
                    }
                }, {
                    id: 'photoHtml',
                    hidetitle: true,
                    html: config.record.object.image ? '<img src="/' + config.record.object.image + '" style="width: 100%; max-width: 300px; height: auto; max-height: 300px; margin-top:10px">' : '<img src="#">'
                }, {
                    xtype: 'numberfield',
                    fieldLabel: _('teachertest_item_count_questions'),
                    name: 'count_questions',
                    id: config.id + '-count_questions',
                }, {
                    xtype: 'xcheckbox',
                    boxLabel: _('teachertest_item_active'),
                    name: 'status',
                    id: config.id + '-active',
                }]
            }, {
                title: _('teachertest_question_names'),
                layout: 'anchor',
                items: [{
                    xtype: 'teachertest-grid-questions',
                    fieldLabel: _('teachertest_item_question'),
                    record: config.record.object
                }],

            }, {
                title: _('teachertest_test_balls'),
                layout: 'anchor',
                items: [{
                    xtype: 'teachertest-grid-balls',
                    fieldLabel: _('teachertest_item_balls'),
                    record: config.record.object
                }],

            }],
        }]
    },

    loadDropZones: function () {
    }

});
Ext.reg('teachertest-item-window-update', teacherTest.window.UpdateItem);