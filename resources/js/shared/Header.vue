<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
const page = usePage();
const appName = computed(() => page.props.appName);
</script>
<template>
    <div class="container">
        <header class="d-flex justify-content-center py-3">
            <div class="col-md-3 mb-2 mb-md-0" style="margin-left: 50px">
                <Link href="/" class="d-inline-flex link-body-emphasis">
                    <span class="fs-4"> {{ appName }}</span>
                </Link>
            </div>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <Link
                        href="/"
                        :class="{ 'nav-link active': $page.url === '/' }"
                        class="nav-link"
                        aria-current="page"
                        >Home</Link
                    >
                </li>
                <li class="nav-item">
                    <Link
                        href="/quiz"
                        :class="{ 'nav-link active': $page.url === '/quiz' }"
                        class="nav-link"
                        >Quiz</Link
                    >
                </li>
                <li class="nav-item">
                    <Link
                        href="/questions"
                        :class="{
                            'nav-link active': $page.url === '/questions',
                        }"
                        class="nav-link"
                        >Question</Link
                    >
                </li>
            </ul>
        </header>
    </div>
</template>

<style lang="scss" scoped></style>
