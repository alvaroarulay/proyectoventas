<template>
<div v-if="validationErrors" class="alert alert-dismissible alert-danger">
    <div class="text-center">
        <strong><div v-for="(value, key, index) in validationErrors" v-text="value"></div></strong>
    </div>
</div>
</template>
<script>
export default {
    data(){ return {}},
    props: ['errors'],
    computed: {
        validationErrors(){
            let errors = Object.values(this.errors);
            errors = errors.flat();
            return errors;
        }
    }
}
</script>