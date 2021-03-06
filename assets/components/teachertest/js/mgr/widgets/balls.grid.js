teacherTest.grid.Balls = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'teachertest-grid-balls';
    }
    Ext.applyIf(config, {
        url: teacherTest.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/balls/getlist',
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
    teacherTest.grid.Balls.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(teacherTest.grid.Balls, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = teacherTest.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    createItem: function (btn, e) {

        var w = MODx.load({
            xtype: 'teachertest-balls-window-create',
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
                action: 'mgr/balls/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = Ext.getCmp('teachertest-balls-window-update');
                        if(w) {
                            w.close();
                        }
                        w = MODx.load({
                            xtype: 'teachertest-balls-window-update',
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
                ? _('teachertest_balls_remove')
                : _('teachertest_balls_remove'),
            text: ids.length > 1
                ? _('teachertest_balls_remove_confirm')
                : _('teachertest_balls_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/balls/remove',
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
        return ['id', 'level', 'min_balls', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('teachertest_item_id'),
            dataIndex: 'id',
            sortable: true,
            width: 70
        }, {
            header: _('teachertest_balls_level'),
            dataIndex: 'level',
            sortable: true,
            width: 200,
            renderer: this._renderLevel,
        }, {
            header: _('teachertest_balls_min'),
            dataIndex: 'min_balls',
            sortable: true,
            width: 200,
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
            text: '<i class="icon icon-plus"></i>&nbsp;' + _('teachertest_balls_create'),
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
    _renderLevel: function (value) {
        if(value == 1){
            return _('teachertest-combo-balls-types-level-first')
        } else if(value == 2){
            return _('teachertest-combo-balls-types-level-two')
        } else if(value == 3){
            return _('teachertest-combo-balls-types-level-three')
        } else if(value == 4){
            return _('teachertest-combo-balls-types-level-four')
        }
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

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _clearSearch: function () {
        this.getStore().baseParams.query = '';
        this.getBottomToolbar().changePage(1);
    },
});
Ext.reg('teachertest-grid-balls', teacherTest.grid.Balls);
