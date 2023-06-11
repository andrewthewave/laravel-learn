<template>
    <Head title="Магазины" />
    <div class="g-titlebar">
        <h1>Магазины</h1>
        <Link href="/shops/create/" class="g-button outlined"> Создать магазин </Link>
    </div>

    <el-table :data="shops.data" table-layout="auto">
        <el-table-column label="Название">
            <template #default="{ row }">
                <Link :href="`/shops/${row.id}/`">{{ row.title }}</Link>
            </template>
        </el-table-column>
        <el-table-column label="Ссылка" prop="url" />
        <el-table-column label="Добавлен">
            <template #default="{ row }">
                {{ (row.created_at || "").split("T")[0] }}
            </template>
        </el-table-column>
        <el-table-column align="right">
            <template #default="{ row }">
                <a
                    @click="deleteShop(row.id, row.title)"
                    title="Удалить"
                    class="g-actionicon"
                >
                    <font-awesome-icon icon="trash" />
                </a>
                <Link :href="`/shops/${row.id}/edit/`" class="g-actionicon">
                    <font-awesome-icon icon="pen-to-square" />
                </Link>
            </template>
        </el-table-column>
    </el-table>
    <b-pagination :links="shops.links" />
</template>

<script>
import { router } from "@inertiajs/vue3";


export default {
    props: {
        shops: Object,
    },
    methods: {
        deleteShop(id, title) {
            if (!confirm(`Вы действительно хотите удалить магазин «${title}»?`)) return;
            router.delete(`/shops/${id}`);
        },
    },
};
</script>
