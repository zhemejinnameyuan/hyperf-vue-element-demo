<template>
    <div class="app-container">
        <div class="top-btn-container">
            <el-button type="primary" size="small" @click="handleAdd()" icon="el-icon-plus">新增分类</el-button>
        </div>

        <div class="search-container">
            <el-input size="small" style="width: 150px;" v-model="searchData.name" placeholder="输入分类名" @keyup.enter.native="search"></el-input>



            <el-button type="success" size="small" @click="search"  style="margin-left: 5px;">搜索</el-button>
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
        <el-dialog :visible.sync="dialogVisible" :title="dialogType==='edit'?'编辑分类':'新增分类'">
            <el-form :model="dataInfo" :rules="rules" ref="ruleForm" label-width="130px" label-position="left">
                <el-form-item label="id" prop="id">
                    <el-input v-model="dataInfo.id" placeholder="id" disabled/>
                </el-form-item>
                <el-form-item label="分类名称" prop="name">
                    <el-input v-model="dataInfo.name" placeholder="分类名称" aria-required="true"/>
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
    import CommonTable from '../../components/CommonTable'
    import {mergeJson} from "../../utils";
    import {getSiteTypeDataList, siteTypeDelete, siteTypeSave} from "../../api/hotArticle/site";

    export default {
        components: {CommonTable},
        data() {
            return {
                commonTable: {
                    loading: true,
                    showSummary:false,
                    exportExcelBtn: true,
                    totalCount: 0,
                    pages: {
                        pageSize: 10,
                        currentPage: 1,
                    },
                    dataList: [],
                    columns: [
                        {prop: 'id', label: 'id', width: "100"},
                        {prop: 'name', label: '分类名称', width: "150"},
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
                    name: '',
                },
                dialogVisible: false,
                dialogType: 'new',
                dataInfo: [],
                rules: {
                    name: [{required: true, message: '请输入分类名称', trigger: 'blur'}],
                },
            }
        },
        mounted() {
            //初始化
            this.getDataList();

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

                this.dataInfo = {"id": 0, "status": 1}
                this.rules.password = {required: true, message: '请输入密码', trigger: 'blur'}
            },
            //编辑
            handleEdit(row) {
                this.dialogType = 'edit'
                this.dialogVisible = true

                this.dataInfo = JSON.parse(JSON.stringify(row))
                this.dataInfo.password = '';
                this.rules.password = '';
            },
            //删除
            handleDelete(row) {
                this.$confirm('确定要删除此数据吗？', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(async () => {
                    var response = await siteTypeDelete(row.id)
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
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        siteTypeSave(this.dataInfo).then(response => {
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
                var response = await getSiteTypeDataList(params)


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
