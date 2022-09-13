<template>
    <v-breadcrumbs :items="items">
      <template v-slot:divider>
        <v-icon>mdi-forward</v-icon>
      </template>
    </v-breadcrumbs>
</template>
<script>
export default {
    name: "Breadcrumbs",
    props: ["items"]
}
</script>