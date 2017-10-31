teacherTest.window.CreateBalls = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'teachertest-balls-window-create';
    }
    Ext.applyIf(config, {
        title: _('teachertest_balls_name'),
        modal:true,
        width: 670,
        autoHeight: true,
        url: teacherTest.config.connector_url,
        action: 'mgr/balls/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }],

    });
    teacherTest.window.CreateBalls.superclass.constructor.call(this, config);
};
Ext.extend(teacherTest.window.CreateBalls, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'test_id',
            id: config.id + '-test-id',
            anchor: '99%',
            allowBlank: false,
            value: config.value
        }, {
            xtype: 'numberfield',
            fieldLabel: _('teachertest_balls_min'),
            name: 'min_balls',
            id: config.id + '-min-balls',
            anchor: '99%'
        }, {
            xtype: 'teachertest-combo-balls-level',
            fieldLabel: _('teachertest_balls_level'),
            name: 'level',
            id: config.id + '-level',
            anchor: '99%'
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('teachertest-balls-window-create', teacherTest.window.CreateBalls);


teacherTest.window.UpdateBalls = function (config) {
    config = config || {};

    if (!config.id) {
        config.id = 'teachertest-balls-window-update';
    }

    Ext.applyIf(config, {
        title: _('teachertest_balls_name'),
        width: 700,
        autoHeight: true,
        url: teacherTest.config.connector_url,
        action: 'mgr/balls/update',
        fields: this.getFields(config),
        modal:true,
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    teacherTest.window.UpdateBalls.superclass.constructor.call(this, config);
};
Ext.extend(teacherTest.window.UpdateBalls, MODx.Window, {

    getFields: function (config) {
        return [ {
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
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
            xtype: 'numberfield',
            fieldLabel: _('teachertest_balls_min'),
            name: 'min_balls',
            id: config.id + '-min-balls',
            anchor: '99%'
        }, {
            xtype: 'teachertest-combo-balls-level',
            fieldLabel: _('teachertest_balls_level'),
            name: 'level',
            id: config.id + '-level',
            anchor: '99%'
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('teachertest-balls-window-update', teacherTest.window.UpdateBalls);