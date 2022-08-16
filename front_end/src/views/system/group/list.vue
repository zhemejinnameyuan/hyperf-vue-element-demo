<template>
    <div class="app-container">
        <div class="top-btn-container">
            <el-button type="primary" size="small" @click="handleAdd()" icon="el-icon-plus">新增分组</el-button>
        </div>

        <div class="search-container">
            <el-select size="small" v-model="searchData.status" placeholder="状态">
                <el-option label="--请选择状态--" :value="-1"></el-option>
                <el-option
                        v-for="item in statusOptions"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                </el-option>
            </el-select>

            <el-button type="success" size="small" @click="search" style="margin-left: 5px;">搜索</el-button>
        </div>

        <!--表格组件-->
        <CommonTable
                :tableLoading="commonTable.loading"
                :showSummary="commonTable.showSummary"
                :exportExcelBtn="commonTable.exportExcelBtn"
                :columns="commonTable.columns"
                :dataList="commonTable.dataList"
                :operatesBtn="commonTable.operatesBtn"
                :pages="commonTable.pages"
                :totalCount="commonTable.totalCount"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange = "handleCurrentChange"
                @handleCommand="handleCommand"
        >
        </CommonTable>

        <!--模态框-->
        <el-dialog :visible.sync="dialogVisible" :title="dialogType==='edit'?'编辑分组':'新增分组'">
            <el-form :model="dataInfo" :rules="rules" ref="ruleForm" label-width="120px" label-position="left">
                <el-form-item label="id" prop="id">
                    <el-input v-model="dataInfo.id" placeholder="id" disabled/>
                </el-form-item>
                <el-form-item label="名称" prop="name">
                    <el-input v-model="dataInfo.name" placeholder="名称" aria-required="true"/>
                </el-form-item>

                <el-form-item label="Menus" prop="rule_ids">
                    <el-tree

                            ref="tree"
                            :check-strictly="treeConf.checkStrictly"
                            :data="treeConf.menuNodeData"
                            :props="treeConf.defaultProps"
                            :default-checked-keys="treeConf.checkedNode"
                            show-checkbox
                            node-key="id"
                            class="permission-tree"
                    />
                </el-form-item>

                <el-form-item label="状态" prop="status">
                    <el-radio v-model="dataInfo.status" :label="0">禁用</el-radio>
                    <el-radio v-model="dataInfo.status" :label="1">启用</el-radio>
                </el-form-item>


            </el-form>
            <div style="text-align:right;">
                <el-button type="danger" @click="cancelForm('ruleForm')">关闭</el-button>
                <el-button type="primary" @click="confirmRole('ruleForm')">提交</el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
    import CommonTable from '../../../components/CommonTable'
    import {mergeJson} from "../../../utils";
    import {getMenuTree, getUserGroupDataList, userGroupDelete, userGroupSave} from "../../../api/system/group";

    export default {
        components: {CommonTable},
        data() {
            return {
                commonTable: {
                    loading: true,
                    showSummary: false,
                    exportExcelBtn: false,
                    totalCount: 0,
                    pages: {
                        pageSize: 10,
                        currentPage: 1,
                    },
                    dataList: [],
                    columns: [
                        {prop: 'id', label: 'ID', width: "100"},
                        {prop: 'name', label: '名称', width: "150"},
                        {
                            prop: 'status', label: '状态', width: "150", formatter: (row) => {
                                if (row.status == 1) {
                                    return "<el-tag class='el-tag el-tag--success el-tag--light' >启用</el-tag>";
                                } else {
                                    return '<el-tag class="el-tag el-tag--danger el-tag--light" >禁用</el-tag>';
                                }
                            }
                        },
                        {prop: 'created_at', label: '创建时间', width: "170"},
                        {prop: 'updated_at', label: '更新时间', width: "170"}
                    ],
                    operatesBtn: [
                        {
                            label: '编辑',
                            type: 'normal',
                            functionName: 'edit',
                            icon: "el-icon-edit"
                        },
                        {
                            label: '删除',
                            type: 'danger',
                            functionName: 'delete',
                            icon: "el-icon-delete"
                        },
                    ]
                },

                searchData: {
                    username: '',
                    group_id: -1,
                    status: -1
                },
                dialogVisible: false,
                dialogType: 'new',
                dataInfo: [],
                rules: {
                    name: [{required: true, message: '请输入名称', trigger: 'blur'}],
                },
                groupOptions: [],
                statusOptions: [{'id': 1, 'name': "启用"}, {'id': 0, 'name': "禁用"}],
                treeConf: {
                    menuNodeData:[],
                    checkStrictly:false,
                    checkedNode:[],
                    defaultProps: {
                        children: 'children',
                        label: 'name'
                    }
                },

            }
        },
        mounted() {
            //初始化
            this.getDataList();

            //菜单分组
            getMenuTree().then(response => {
                this.treeConf.menuNodeData = response.data
            })

        },
        methods: {
            // 搜索
            search() {
                this.handleCurrentChange(1)
            },
            //新增
            handleAdd() {
                this.dialogType = 'new'
                this.dialogVisible = true

                this.treeConf.checkedNode = [];
                this.$nextTick(() => {
                    this.$refs.tree.setCheckedKeys([]);
                })

                this.dataInfo = {"id": 0, "status": 1}
            },
            //编辑
            handleEdit(row) {
                this.dialogType = 'edit'
                this.dialogVisible = true

                this.dataInfo = JSON.parse(JSON.stringify(row))

                this.treeConf.checkStrictly = true;
                this.$nextTick(() => {
                    this.$refs.tree.setCheckedKeys([]);
                    this.treeConf.checkedNode = this.dataInfo.rule_ids.split(',')

                    this.treeConf.checkStrictly = false;
                })
            },
            //删除
            handleDelete(row) {
                this.$confirm('确定要删除此数据吗？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(async () => {
                    var response = await userGroupDelete(row.id)
                    this.$message({
                        type: 'success',
                        message: response.msg
                    })
                    this.getDataList()
                }).catch(err => {
                    console.error(err)
                })
            },
            //提交
            confirmRole(formName) {
                //子节点 和 父节点
                const tree_rule_ids = this.$refs.tree.getCheckedKeys().concat(this.$refs.tree.getHalfCheckedKeys());
                this.dataInfo.rule_ids = tree_rule_ids.join(',');

                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        userGroupSave(this.dataInfo).then(response => {
                            this.dialogVisible = false
                            this.$notify({
                                title: 'Success',
                                dangerouslyUseHTMLString: true,
                                message: response.msg,
                                type: 'success'
                            })
                            this.getDataList()
                        }).catch(error => {

                        })

                    } else {
                        return false;
                    }
                });
            },
            cancelForm(formName) {
                this.$refs[formName].resetFields();

                this.dialogVisible = false
            },
            //获取数据
            async getDataList() {
                //拼装分页和查询参数
                var params = mergeJson(this.commonTable.pages, this.searchData)
                var response = await getUserGroupDataList(params)


                this.commonTable.dataList = response.data.data
                this.commonTable.totalCount = response.data.count
                this.commonTable.loading = false
            },
            // 切换每页显示的数量
            handleSizeChange(size) {
                this.commonTable.pages.pageSize = size;
                this.getDataList();
            },
            // 切换页码
            handleCurrentChange(index) {
                this.commonTable.pages.currentPage = index;
                this.getDataList();
            },
            // 操作按钮
            handleCommand(command, row) {
                switch (command) {
                    case 'edit':
                        this.handleEdit(row)
                        break;
                    case 'delete':
                        this.handleDelete(row)
                        break;
                }
            }
        }
    }
</script>
