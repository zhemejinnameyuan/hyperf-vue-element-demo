<template>
    <div class="dashboard-container">
        <el-table
                :data="tableData"
                style="width: 100%">
            <el-table-column
                    prop="key"
                    label="参数"
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="value"
                    label="值"
                    width="300">
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import {getSysInfo} from "../../api/public";

    export default {
      name: 'Dashboard',
      computed: {
        ...mapGetters([
          'name'
        ])
      },
      data() {
        return {
          tableData: []
        }
      },
      mounted() {
        getSysInfo().then(response => {
          this.tableData = response.data
        })
      }
    }
</script>

<style lang="scss" scoped>
    .dashboard {
        &-container {
            margin: 30px;
        }

        &-text {
            font-size: 30px;
            line-height: 46px;
        }
    }
</style>
