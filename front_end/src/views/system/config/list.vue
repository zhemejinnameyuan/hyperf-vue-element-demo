<template>
    <div class="app-container">
        <div class="top-btn-container">
            <el-button type="primary" size="small" @click="handleAdd()" icon="el-icon-plus">新增配置</el-button>
        </div>

        <div class="search-container">
            <el-input size="small" style="width: 150px;" v-model="searchData.key" placeholder="输入配置key" @keyup.enter.native="search"></el-input>


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
        <el-dialog :visible.sync="dialogVisible" :title="dialogType==='edit'?'编辑配置':'新增配置'">
            <el-form :model="dataInfo" :rules="rules" ref="ruleForm" label-width="130px" label-position="left">
                <el-form-item label="id" prop="id">
                    <el-input v-model="dataInfo.id" placeholder="id" disabled/>
                </el-form-item>
                <el-form-item label="配置项" prop="key">
                    <el-input v-model="dataInfo.key" placeholder="配置项" aria-required="true"/>
                </el-form-item>
                <el-form-item label="值" prop="value">
                    <el-input v-model="dataInfo.value" placeholder="值" aria-required="true"/>
                </el-form-item>
                <el-form-item label="描述" prop="desc">
                    <el-input v-model="dataInfo.desc" placeholder="描述" aria-required="true"/>
                </el-form-item>
                <el-form-item label="类型" prop="type">
                    <el-radio v-model="dataInfo.type" label="text">text</el-radio>
                    <el-radio v-model="dataInfo.type" label="json">json</el-radio>
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
    import {addConfig, deleteConfig, getConfig, saveConfig, updateConfig} from "../../../api/system/config";

    export default {
        components: {CommonTable},
        data() {
            return {
                commonTable: {
                    loading: true,
                    showSummary:false,
                    exportExcelBtn: false,
                    totalCount: 0,
                    pages: {
                        pageSize: 10,
                        currentPage: 1,
                    },
                    dataList: [],
                    columns: [
                        {prop: 'id', label: 'id', width: "100"},
                        {prop: 'key', label: '配置项', width: "150"},
                        {prop: 'value', label: '值', width: "150"},
                        {prop: 'type', label: '类型', width: "150"},
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
                    key: '',
                },
                dialogVisible: false,
                dialogType: 'new',
                dataInfo: [],
                rules: {
                    key: [{required: true, message: '请输入配置项', trigger: 'blur'}],
                    value: [{required: true, message: '请输入值', trigger: 'blur'}],
                    desc: [{required: true, message: '请输入描述', trigger: 'blur'}],
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

                this.dataInfo = {"id": 0, "status": 1, 'type': 'text'}
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
                    let response = await deleteConfig(row.id)
                    this.$message.success(response.message)
                    this.getDataList()
                })
            },
            //提交
            confirmRole(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        if(this.dataInfo.id >0){
                            updateConfig(this.dataInfo).then(response => {
                                this.dialogVisible = false
                                this.$message.success(response.message)
                                this.getDataList()
                            })
                        }else{
                            addConfig(this.dataInfo).then(response => {
                                this.dialogVisible = false
                                this.$message.success(response.message)
                                this.getDataList()
                            })
                        }

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
                let params = mergeJson(this.commonTable.pages, this.searchData)
                let response = await getConfig(params)


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
