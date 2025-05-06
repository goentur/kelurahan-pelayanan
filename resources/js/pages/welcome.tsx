import { Head, Link, usePage } from '@inertiajs/react';

import AuthLayout from '@/layouts/auth-layout';
import { SharedData } from '@/types';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;
    return (
        <AuthLayout title="SELAMAT DATANG KEMBALI" description="Di Layanan Pajak Bumi dan Bangunan">
            <Head title="Home" />
            
            <nav className="flex items-center justify-center gap-4">
                {auth.user ? (
                    <Link
                        href={route('beranda')}
                        className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Beranda
                    </Link>
                ) : (
                    <>
                        <Link
                            href={route('login')}
                            className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                        >
                            Log in
                        </Link>
                    </>
                )}
            </nav>
        </AuthLayout>
    );
}
