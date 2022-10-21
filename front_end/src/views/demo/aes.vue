<template>
    <div class="app-container">
        <label>原始字符串:{{oriData}}</label><br>
        <label>加密后:{{encryptData}}</label><br>
        <label>解密后:{{decryptData}}</label><br>
    </div>
</template>


<script>

    import {Decrypt, Encrypt} from "../../vendor/Aes";

    export default {
        name: 'DemoAes',
        data() {
            return {
                oriData: 'hyperf-admin',
                encryptData: '',
                decryptData: '',
            };
        },
        methods: {


        },
        mounted() {
            this.encryptData = Encrypt(this.oriData)
            this.decryptData = Decrypt(this.encryptData)
        }
    }
</script>
