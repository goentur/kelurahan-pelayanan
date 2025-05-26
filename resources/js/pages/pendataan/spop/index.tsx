import DataTableFilters from '@/components/data-table/filters'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { alertApp } from '@/components/utils'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem, InfoDataTabel } from '@/types'
import { Head, useForm } from '@inertiajs/react'
import axios from 'axios'
import { useEffect, useRef, useState } from 'react'
import DataTable from './components/data-table'
import FormDialog from './components/form-dialog'
import DataTablePagination from '@/components/data-table/pagination'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Pemberkasan',
        href: 'pendataan.spop.index',
    },
    {
        title: 'SPOP',
        href: 'pendataan.spop.index',
    },
];

export default function Index({ gate, status, pekerjaan, tanah }: any) {
    
    const title = 'Form'
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const [form, setForm] = useState(false)
    const [isEdit, setIsEdit] = useState(false)
    const [loading, setLoading] = useState(false)
    const [dataTable, setDataTable] = useState<[]>([])
    const [linksPagination, setLinksPagination] = useState([])
    const [hapus, setHapus] = useState(false)
    const [infoDataTabel, setInfoDataTabel] = useState<InfoDataTabel>({
        page: 1,
        from: 0,
        to: 0,
        total: 0,
        perPage: 25,
        search: null,
    })
    const statusOptions = status.map((item:any) => ({
        label: item.nama,
        value: item.id,
    }));
    const pekerjaanOptions = pekerjaan.map((item:any) => ({
        label: item.nama,
        value: item.id,
    }));
    const tanahOptions = tanah.map((item:any) => ({
        label: item.nama,
        value: item.id,
    }));
    const { data, setData, errors, post, patch, reset, processing} = useForm({
        kd_propinsi : "",
        kd_dati2 : "",
        kd_kecamatan : "",
        kd_kelurahan : "",
        kd_blok : "",
        no_urut : "",
        kd_jns_op : "",
        jalan : "",
        blok_kav_no : "",
        rw : "",
        rt : "",
        luas_tanah : "",
        no_sertipikat : "",
        tanah : "",
        koordinat : null,
        status : "",
        pekerjaan : "",
        nik : "",
        npwp : "",
        nama : "",
        jalan_sp : "",
        blok_kav_no_sp : "",
        rw_sp : "",
        rt_sp : "",
        kelurahan : "",
        kecamatan : "",
        kota : "",
        kode_pos : "",
        no_telp : "",
        email : "",
    });
    useEffect(() => {
        getData();
    }, []);
    
    useEffect(() => {
        getData();
    }, [infoDataTabel.page, infoDataTabel.perPage]);

    const getData = async () => {
        setLoading(true);
        try {
            const response = await axios.post(route('pendataan.spop.data'), {
                page: infoDataTabel.page,
                perPage: infoDataTabel.perPage,
                // berdasarkan: data.berdasarkan,
                // search: data.search,
            });
            setDataTable(response.data.data);
            setLinksPagination(response.data.links);
            setInfoDataTabel((prev) => ({
                ...prev,
                page: response.data.current_page,
                from: response.data.from,
                to: response.data.to,
                total: response.data.total,
                perPage: response.data.per_page,
            }));
        } catch (error:any) {
            alertApp(error.response.data.message, 'error');
        } finally {
            setLoading(false);
        }
    };
    const handleForm = (e: React.FormEvent) => {
        e.preventDefault()
        const action = isEdit ? patch : post
        const routeName = isEdit
            ? (route('pendataan.spop.update', data) as string)
            : (route('pendataan.spop.store') as string)
        action(routeName, {
            preserveScroll: true,
            onSuccess: (e) => {
                reset()
                alertApp(e)
                setForm(false)
            },
            onError: (e) => {
                const firstErrorKey = Object.keys(e)[0]
                if (firstErrorKey) {
                    formRefs.current[firstErrorKey]?.focus()
                }
            },
        })
    }
    const cekNop = async () => {
        try {
            const response = await axios.post(route('pendataan.spop.cek-nop'), data);
            if (!response.data.status) {
                alertApp(response.data.message,"error")
            }
        } catch (error:any) {
            alertApp(error.response.data.message, 'error');
        }
    };
    useEffect(() => {
        const allFilled = data.kd_propinsi && data.kd_dati2 && data.kd_kecamatan && data.kd_kelurahan && data.kd_blok && data.no_urut && data.kd_jns_op;
        if (allFilled) {
            cekNop();
        }
    }, [data.kd_propinsi, data.kd_dati2, data.kd_kecamatan, data.kd_kelurahan, data.kd_blok, data.no_urut, data.kd_jns_op]);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-xl">{title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <DataTableFilters
                            gate={gate}
                            setInfoDataTabel={setInfoDataTabel}
                            onClick={() => {reset(), setForm(true), setIsEdit(false)}}
                        />
                        <DataTable
                            gate={gate}
                            loading={loading}
                            data={dataTable}
                            from={infoDataTabel.from}
                            setForm={setForm}
                            setIsEdit={setIsEdit}
                            setData={setData}
                            setHapus={setHapus}
                        />
                        <DataTablePagination
                            infoDataTabel={infoDataTabel}
                            setInfoDataTabel={setInfoDataTabel}
                            linksPagination={linksPagination}
                        />
                    </CardContent>
                </Card>
            </div>
            <FormDialog
                open={form}
                setOpen={setForm}
                title={title}
                data={data}
                setData={setData}
                errors={errors}
                formRefs={formRefs}
                processing={processing}
                handleForm={handleForm}
                tanahOptions={tanahOptions}
                statusOptions={statusOptions}
                pekerjaanOptions={pekerjaanOptions}
            />
            {/* <Delete
                open={hapus}
                setOpen={setHapus}
                processing={processing}
                handleHapusData={handleHapus}
            />  */}
        </AppLayout>
    )
}
