<template>
    <div class="app-container">
        <div class="top-btn-container">
            <el-button type="primary" size="small" @click="handleAdd()" icon="el-icon-plus">新增用户</el-button>
        </div>

        <div class="search-container">
            <el-input size="small" style="width: 150px;" v-model="searchData.username" placeholder="输入用户名" @keyup.enter.native="search"></el-input>

            <el-select size="small" v-model="searchData.group_id" placeholder="用户组">
                <el-option  label="--请选择用户组--" :value="-1"></el-option>
                <el-option
                        v-for="item in groupOptions"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                </el-option>
            </el-select>

            <el-select size="small" v-model="searchData.status" placeholder="状态">
                <el-option label="--请选择状态--" :value="-1"></el-option>
                <el-option
                        v-for="item in userStatusOptions"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                </el-option>
            </el-select>


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
        <el-dialog :visible.sync="dialogVisible" :title="dialogType==='edit'?'编辑用户':'新增用户'">
            <el-form :model="dataInfo" :rules="rules" ref="ruleForm" label-width="130px" label-position="left">
                <el-form-item label="用户ID" prop="id">
                    <el-input v-model="dataInfo.id" placeholder="用户ID" disabled/>
                </el-form-item>
                <el-form-item label="昵称" prop="nickname">
                    <el-input v-model="dataInfo.nickname" placeholder="昵称" aria-required="true"/>
                </el-form-item>
                <el-form-item label="用户名" prop="username">
                    <el-input v-model="dataInfo.username" placeholder="用户名" aria-required="true"/>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input type="password" v-model="dataInfo.password" placeholder="密码,不填写不修改" aria-required="true"/>
                </el-form-item>
                <el-form-item label="密码输入错误次数" prop="password_error_count">
                    <el-input type="number" v-model="dataInfo.password_error_count" placeholder="密码输入错误次数" aria-required="true"/>
                </el-form-item>
                <el-form-item label="用户组" prop="group_id">
                    <el-select v-model="dataInfo.group_id" placeholder="请选择用户组">
                        <el-option
                                v-for="item in groupOptions"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
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
    import {getUserGroupOptionDataList, deleteUser, getUser, updateUser, addUser} from "../../../api/system/user";

    export default {
        name: 'SystemUserList',
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
                        {prop: 'id', label: '用户ID', width: "100"},
                        {prop: 'username', label: '用户名', width: "150"},
                        {prop: 'nickname', label: '昵称', width: "150"},
                        {
                            prop: 'status', label: '状态', width: "150", formatter: (row) => {
                                if (row.status == 1) {
                                    return "<el-tag class='el-tag el-tag--success el-tag--light' >启用</el-tag>";
                                } else {
                                    return '<el-tag class="el-tag el-tag--danger el-tag--light" >禁用</el-tag>';
                                }
                            }
                        },
                        {prop: 'group_name', label: '所属分组', width: "170"},
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
                    nickname: [{required: true, message: '请输入昵称', trigger: 'blur'}],
                    username: [{required: true, message: '请输入用户名', trigger: 'blur'}],
                },
                groupOptions: [],
                userStatusOptions: [{'id': 1, 'name': "启用"}, {'id': 0, 'name': "禁用"}]
            }
        },
        mounted() {
            //初始化
            this.getDataList();

            //获取权限分组
            getUserGroupOptionDataList().then(response => {
                this.groupOptions = response.data
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
                confirmRequest('确定要删除此数据吗?',async ()=>{
                    let response = await deleteUser(row.id)
                    this.$message.success(response.message)
                    this.getDataList()
                })
            },
            //提交
            confirmRole(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        if(this.dataInfo.id > 0){
                            updateUser(this.dataInfo).then(response => {
                                this.dialogVisible = false
                                this.$message.success(response.message)
                                this.getDataList()
                            })
                        }else{
                            addUser(this.dataInfo).then(response => {
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
                let response = await getUser(params)


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
