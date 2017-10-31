var teacherTest = function (config) {
    config = config || {};
    teacherTest.superclass.constructor.call(this, config);
};
Ext.extend(teacherTest, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('teachertest', teacherTest);

teacherTest = new teacherTest();