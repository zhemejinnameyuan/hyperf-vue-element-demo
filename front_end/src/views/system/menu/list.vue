<template>
    <div class="app-container">
        <el-button v-if="searchData.pid > '0'" type="primary" size="small" @click="$router.back('')">
            <i class="el-icon-back"/>
        </el-button>
        <el-button type="primary" size="small" @click="handleAdd()" icon="el-icon-plus">新增菜单</el-button>

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
                :columns="commonTable.columns"
                :showSummary="commonTable.showSummary"
                :exportExcelBtn="commonTable.exportExcelBtn"
                :dataList="commonTable.dataList"
                :operatesBtn="commonTable.operatesBtn"
                :pages="commonTable.pages"
                :totalCount="commonTable.totalCount"
                @handleSizeChange="handleSizeChange"
                @handleCurrentChange="handleCurrentChange"
                @handleCommand="handleCommand"
        >
        </CommonTable>

        <!--模态框-->
        <el-dialog :visible.sync="dialogVisible" :title="dialogType==='edit'?'编辑菜单':'新增菜单'">
            <el-form :model="dataInfo" :rules="rules" ref="ruleForm" label-width="120px" label-position="left">
                <el-form-item label="id" prop="id">
                    <el-input v-model="dataInfo.id" placeholder="id" disabled/>
                </el-form-item>
                <el-form-item label="菜单名称" prop="name">
                    <el-input v-model="dataInfo.name" placeholder="name" aria-required="true"/>
                </el-form-item>
                <el-form-item label="前端路由路径" prop="path">
                    <el-input v-model="dataInfo.path" placeholder="path" aria-required="true"/>
                </el-form-item>
                <el-form-item label="菜单icon" prop="icon">
                    <el-input v-model="dataInfo.icon" placeholder="icon"/>
                </el-form-item>
                <el-form-item label="重定向" prop="redirect">
                    <el-input v-model="dataInfo.redirect" placeholder="redirect"/>
                </el-form-item>
                <el-form-item label="组件(视图路径)" prop="component">
                    <el-input v-model="dataInfo.component" placeholder="如:form/index" aria-required="true"/>
                </el-form-item>
                <el-form-item label="序号" prop="sort">
                    <el-input v-model="dataInfo.sort" placeholder="sort"/>
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
    import {confirmRequest, mergeJson} from "../../../utils";
    import {getMenuDataList, menuDelete, menuSave} from "../../../api/system/menu";

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
                        {prop: 'id', label: 'ID', width: "80"},
                        {
                            prop: 'name', label: '菜单名称(点击进入下级)', width: "250", formatter: (row) => {
                                return '<button type="button" class="el-button el-button--normal el-button--small" onclick="handleRedirect(' + row.id + ')">' + row.name + '</button>';
                            }
                        },
                        {prop: 'path', label: '路由路径', width: "150"},
                        {prop: 'redirect', label: '重定向', width: "150"},
                        {prop: 'component', label: '组件(视图路径)', width: "250"},
                        {prop: 'status', label: '状态', width: "100", formatter: (row) => {
                                if (row.status == 1) {
                                    return "<el-tag class='el-tag el-tag--success el-tag--light' >启用</el-tag>";
                                } else {
                                    return '<el-tag class="el-tag el-tag--danger el-tag--light" >禁用</el-tag>';
                                }
                            }
                        },
                        {prop: 'sort', label: '序号', width: "150"}
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
                    'pid': 0,
                    'status': -1,
                },
                dialogVisible: false,
                dialogType: 'new',
                dataInfo: [],
                rules: {
                    name: [{required: true, message: '请输入菜单名称', trigger: 'blur'}],
                    path: [{required: true, message: '请输入路由路径', trigger: 'blur'}],
                    component: [{required: true, message: '请输入组件', trigger: 'blur'}]
                },
                statusOptions: [{'id': 1, 'name': "启用"}, {'id': 0, 'name': "禁用"}]
            }
        },
        mounted() {
            window.handleRedirect = (pid) => {
                this.$router.push(`/system/menu?pid=${pid}`)
            },
                //初始化
                this.getDataList();
        },
        watch: {
            // 监听,当路由发生变化的时候执行
            $route: {
                handler: function () {
                    this.getPid()
                    this.getDataList()
                },
            }
        },
        methods: {
            getPid() {
                if (this.$route.query.pid === undefined) {
                    this.searchData.pid = 0;
                } else {
                    this.searchData.pid = this.$route.query.pid;
                }
            },

            // 搜索
            search() {
                this.handleCurrentChange(1)
            },
            //新增
            handleAdd() {
                this.dialogType = 'new'
                this.dialogVisible = true

                this.dataInfo = {"id": 0, "status": 1, 'pid': this.searchData.pid}
            },
            //编辑
            handleEdit(row) {
                this.dialogType = 'edit'
                this.dialogVisible = true

                this.dataInfo = JSON.parse(JSON.stringify(row))
            },
            //删除
            handleDelete(row) {
                confirmRequest('确定要删除此数据吗?',async ()=>{
                    let response = await menuDelete(row.id)
                    this.$message.success(response.message)
                    this.getDataList()
                })
            },
            //提交
            confirmRole(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        menuSave(this.dataInfo).then(response => {
                            this.dialogVisible = false
                            this.$message.success(response.message)
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
                let params = mergeJson(this.commonTable.pages, this.searchData);
                let response = await getMenuDataList(params);


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
