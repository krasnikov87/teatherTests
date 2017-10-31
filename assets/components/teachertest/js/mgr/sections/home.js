teacherTest.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'teachertest-panel-home',
            renderTo: 'teachertest-panel-home-div'
        }]
    });
    teacherTest.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(teacherTest.page.Home, MODx.Component);
Ext.reg('teachertest-page-home', teacherTest.page.Home);