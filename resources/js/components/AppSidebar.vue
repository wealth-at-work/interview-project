<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { register, login, home } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LogIn, House, ClipboardList } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;

let mainNavItems: NavItem[] = [];

if (!user) {
    mainNavItems = [
        {
            title: 'register',
            href: register(),
            icon: ClipboardList,
        },
        {
            title: 'login',
            href: login(),
            icon: LogIn,
        },
    ];
}

mainNavItems.push(
    {
        title: 'home',
        href: home(),
        icon: House,
    }
)

const footerNavItems: NavItem[] = [
    {
        title: 'IMDB',
        href: 'https://www.imdb.com/',
        icon: Folder,
    },
    {
        title: 'Goodreads',
        href: 'https://www.goodreads.com/',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="register()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser
                v-if="user"
            />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
