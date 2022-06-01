<template>
  <div class=" font-poppins container-fluid h-100  register">
    <div class="row">
      <div class="col-md-3 register-left">
        <img src="https://image.ibb.co/n7oTvU/logo_white.png" alt=""/>
        <h3>Welcome</h3>
        <p>Create an account in the Workflow System.</p>
      </div>
      <div class="col-md-9 register-right">
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <h3 class="register-heading">Create Staff Account</h3>
            <form ref="regForm" @submit="verifyForm"  method="post" class="row register-form needs-validation" v-bind:action="this.url">
              <div class="col-md-6">
                <div class="form-group p-2 m-2">
                  <input name="first_name" type="text" class="form-control" placeholder="First Name *" value="" required/>
                  <div class="valid-feedback" >
                    Looks good!
                  </div>
                </div>
                <div class="form-group p-2 m-2">
                  <input name="last_name" type="text" class="form-control" placeholder="Last Name *" value="" required/>
                  <div class="valid-feedback">
                    Looks good!
                  </div>
                </div>
                <div class="form-group p-2 m-2">
                  <input  name="password" type="password" class="form-control" placeholder="Password *" v-model="form.password" required/>
                  <div class="invalid-feedback d-block" >
                    {{ errors.password }}
                  </div>
                </div>
                <div class="form-group p-2 m-2">
                  <input  name="password2" type="password" class="form-control"  placeholder="Confirm Password *" v-model="form.password2" required/>
                  <div v-if="errors.password2" class="invalid-feedback d-block">
                    {{ errors.password2 }}
                  </div>
                </div>
                <div class="form-group p-2 m-2">
                  <div class="maxl ">
                    <label class="radio m-2 inline">
                      <input type="radio" name="sex" value=1 checked>
                      <span> Male </span>
                    </label>
                    <label class="radio m-2 inline">
                      <input type="radio" name="sex" value=2>
                      <span>Female </span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group p-2 m-2">
                  <input  name="username" type="text" class="form-control" placeholder="Username *" v-model="form.username" required/>
                  <div class="invalid-feedback d-block">
                    {{ errors.username }}
                  </div>
                </div>
                <div class="form-group p-2 m-2">
                  <input  name="email" type="email" class="form-control" placeholder="Your Email *" v-model="form.email" required/>
                  <div class="invalid-feedback d-block">
                    {{ errors.email }}
                  </div>
                </div>
                <div class="form-group p-2 m-2">
                  <input  name="phone" type="text" minlength="9" maxlength="20"  class="form-control" placeholder="Mobile Phone No *" v-model="form.phone" required/>
                  <div class="invalid-feedback d-block">
                    {{ errors.phone }}
                  </div>
                </div>
                <div class="form-group p-2 m-2">
                  <select  name="role" class="form-control" v-model="form.role">
                    <option class="hidden" selected disabled value="placeholder">Staff Role *</option>
                    <option v-for="(key,value) in roles" :value="value">{{ value }}</option>
                  </select>
                  <div class="invalid-feedback d-block">
                    {{ errors.role }}
                  </div>
                </div>
                <br/>
                <br/>
                <button  type="submit" class="btnRegister" :disabled="btnLoading">
                  <span v-if="btnLoading" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                  Register </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import {verifyRegistration} from "../services/register-service";

export default {
  name: "app-register",
  props: {
    roles: String,
    url: String
  },
  data() {
    return {
      display:'d-block',
      form: {
        username: "",
        password: "",
        password2: "",
        email: "",
        phone: "",
        role: "placeholder",
      },
      errors: {
        username: "",
        password: "",
        password2: "",
        email: "",
        phone: "",
        role: ""
      },
      btnLoading: false
    }
  },
  created() {
    console.log(this.url)
  },
  methods:{
    async verifyForm(e){
      e.preventDefault();
      this.btnLoading =true;
      await verifyRegistration(this.form).then((result)=>{
        this.errors = result.data;
        if(!(this.errors.password || this.errors.password2 || this.errors.email || this.errors.role || this.errors.username || this.errors.phone)){
          this.$refs.regForm.submit();
        }
        else{
          this.btnLoading =false;
        }
      }).catch((e)=>{
        this.btnLoading =false;
      });

    }
  }
}
</script>

<style scoped>

</style>