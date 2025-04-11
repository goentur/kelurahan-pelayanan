import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Building, ChartPie, FileSearch, FileText, Key, LayoutGrid, Medal, Send, UserRoundCog, Users } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavDashboard: NavItem[] = [
    {
        title: "Realisasi",
        href: "dashboard.realisasi.index",
        icon: ChartPie,
        permission: 'dashboard-realisasi',
    },
];
const mainNavTransaksi: NavItem[] = [
    {
        title: "Penyampaian",
        href: "transaksi.penyampaian.index",
        icon: Building,
        permission: 'penyampaian-index',
    },
    {
        title: "Laporan Penyampaian",
        href: "transaksi.laporan-penyampaian.index",
        icon: Send,
        permission: 'laporan-penyampaian-index',
    },
];
const mainNavSppt: NavItem[] = [
    {
        title: "Data",
        href: "sppt.data.index",
        icon: FileSearch,
        permission: 'sppt-data-index',
    },
];
const mainNavMaster: NavItem[] = [
    {
        title: "Satuan Kerja",
        href: "master.satuan-kerja.index",
        icon: Building,
        permission: 'satuan-kerja-index',
    },
    {
        title: "Jabatan",
        href: "master.jabatan.index",
        icon: Medal,
        permission: 'jabatan-index',
    },
    {
        title: "Penyampaian Keterangan",
        href: "master.penyampaian-keterangan.index",
        icon: FileText,
        permission: 'penyampaian-keterangan-index',
    },
    {
        title: "Pegawai",
        href: "master.pegawai.index",
        icon: Medal,
        permission: 'pegawai-index',
    },
    {
        title: "Jenis Lapor",
        href: "master.jenis-lapor.index",
        icon: Send,
        permission: 'jenis-lapor-index',
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Role',
        href: 'role.index',
        icon: UserRoundCog,
        permission: 'role-index',
    },
    {
        title: 'Permission',
        href: 'permission.index',
        icon: Key,
        permission: 'permission-index',
    }
];

export function AppSidebar() {
    const { permissions }: any = usePage().props.auth
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={route('beranda')} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavDashboard} permissions={permissions} title="Dashboard" />
                <NavMain items={mainNavTransaksi} permissions={permissions} title="Transaksi" />
                <NavMain items={mainNavSppt} permissions={permissions} title="SPPT" />
                <NavMain items={mainNavMaster} permissions={permissions} title="Master" />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} permissions={permissions} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
