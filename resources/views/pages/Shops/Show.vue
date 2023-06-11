<template>
    <Head :title="shop.title" />
    <div class="g-row">
        <h1>{{ shop.title }}</h1>
        <Link :href="`/shops/${shop.id}/edit/`" class="g-button outlined"> Редактировать </Link>
        <Link href="/users/" class="right">
            <font-awesome-icon icon="rotate-left" />
            Вернуться к списку магазинов
        </Link>
    </div>
    <div class="g-row">
        <div class="size_6">
            <el-descriptions title="Информация о магазине" column="1">
                <el-descriptions-item label="Название">
                    {{ shop.title }}
                </el-descriptions-item>
                <el-descriptions-item label="URL">
                    {{ shop.url }}
                </el-descriptions-item>
                <el-descriptions-item label="Добавлен">
                    {{ (shop.created_at || "").split("T")[0] }}
                </el-descriptions-item>

            </el-descriptions>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        shop: Object,
    },
};
</script>

<style scoped></style>
