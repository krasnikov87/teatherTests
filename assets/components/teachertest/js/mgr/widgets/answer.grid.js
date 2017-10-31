teacherTest.grid.Answer = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'teachertest-grid-answer';
    }
    Ext.applyIf(config, {
        url: teacherTest.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/answer/getlist',
            question_id: config.record.id
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateItem(grid, e, row);
            }
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec) {
                return !rec.data.active
                    ? 'teachertest-grid-row-disabled'
                    : '';
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
    });
    teacherTest.grid.Answer.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(teacherTest.grid.Answer, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = teacherTest.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    createItem: function (btn, e) {

        var w = MODx.load({
            xtype: 'teachertest-answer-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });

        w.reset();
        w.setValues({
            question_id: Ext.getCmp('question-id').value,
        });
        w.show(e.target);
    },

    updateItem: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/answer/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = Ext.getCmp('teachertest-answer-window-update');
                        if(w) {
                            w.close();
                        }
                        w = MODx.load({
                            xtype: 'teachertest-answer-window-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    removeItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('teachertest_answer_remove')
                : _('teachertest_answer_remove'),
            text: ids.length > 1
                ? _('teachertest_answer_remove_confirm')
                : _('teachertest_answer_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/answer/remove',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
    },


    getFields: function () {
        return ['id', 'answer', 'correct', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('teachertest_item_id'),
            dataIndex: 'id',
            sortable: true,
            width: 70
        }, {
            header: _('teachertest_answer_name'),
            dataIndex: 'answer',
            sortable: true,
            width: 200,
        }, {
            header: _('teachertest_answer_correct'),
            dataIndex: 'correct',
            sortable: true,
            width: 200,
            renderer: teacherTest.utils.renderBoolean
        }, {
            header: _('teachertest_grid_actions'),
            dataIndex: 'actions',
            renderer: teacherTest.utils.renderActions,
            sortable: false,
            width: 100,
            id: 'actions'
        }];
    },

    getTopBar: function () {
        return [{
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('teachertest_answer_create'),
            handler: this.createItem,
            scope: this
        }];
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                }
                else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    },


});
Ext.reg('teachertest-grid-answer', teacherTest.grid.Answer);
