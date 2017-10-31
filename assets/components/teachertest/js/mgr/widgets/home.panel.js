teacherTest.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'teachertest-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('teachertest') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'panel',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                    html: _('teachertest_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'teachertest-grid-items',
                    cls: 'main-wrapper',
                }]

        }]
    });
    teacherTest.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(teacherTest.panel.Home, MODx.Panel);
Ext.reg('teachertest-panel-home', teacherTest.panel.Home);
