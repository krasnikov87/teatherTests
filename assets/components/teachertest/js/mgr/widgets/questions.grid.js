teacherTest.grid.Questions = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'teachertest-grid-questions';
    }
    Ext.applyIf(config, {
        url: teacherTest.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/question/getlist',
            test_id: config.record.id
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
    teacherTest.grid.Questions.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(teacherTest.grid.Questions, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = teacherTest.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    createItem: function (btn, e) {

        var w = MODx.load({
            xtype: 'teachertest-question-window-create',
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
            status: true,
            test_id: Ext.getCmp('item-id').value,
            type: 'radio'
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
                action: 'mgr/question/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = Ext.getCmp('teachertest-question-window-update');
                        if(w) {
                            w.close();
                        }
                        w = MODx.load({
                            xtype: 'teachertest-question-window-update',
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
                ? _('teachertest_questions_remove')
                : _('teachertest_question_remove'),
            text: ids.length > 1
                ? _('teachertest_questions_remove_confirm')
                : _('teachertest_question_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/question/remove',
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

    disableItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/question/disable',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },

    enableItem: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/question/enable',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                },
                failure: {
                  fn: function (response) {
                      
                  }  
                }
            }
        })
    },

    getFields: function () {
        return ['id', 'question', 'status', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('teachertest_item_id'),
            dataIndex: 'id',
            sortable: true,
            width: 70
        }, {
            header: _('teachertest_question_name'),
            dataIndex: 'question',
            sortable: true,
            width: 200,
        }, {
            header: _('teachertest_item_status'),
            dataIndex: 'status',
            renderer: teacherTest.utils.renderBoolean,
            sortable: true,
            width: 100,
        },{
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
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('teachertest_question_create'),
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
    }
});
Ext.reg('teachertest-grid-questions', teacherTest.grid.Questions);
