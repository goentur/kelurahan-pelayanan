import Delete from '@/components/data-table/delete'
import DataTableFilters from '@/components/data-table/filters'
import DataTablePagination from '@/components/data-table/pagination'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { alertApp } from '@/components/utils'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem, IndexGate, InfoDataTabel } from '@/types'
import { Head, useForm } from '@inertiajs/react'
import axios from 'axios'
import { useEffect, useRef, useState } from 'react'
import DataTable from './components/data-table'
import FormDialog from './components/form-dialog'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Master',
        href: 'master.penyampaian-keterangan.index',
    },
    {
        title: 'Penyampaian Keterangan',
        href: 'master.penyampaian-keterangan.index',
    },
];

export default function Index({ gate }: IndexGate) {
    const title = 'Penyampaian Keterangan'
    const [form, setForm] = useState(false)
    const [hapus, setHapus] = useState(false)
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const [loading, setLoading] = useState(false)
    const [isEdit, setIsEdit] = useState(false)
    const [dataTable, setDataTable] = useState<[]>([])
    const [linksPagination, setLinksPagination] = useState([])
    const [infoDataTabel, setInfoDataTabel] = useState<InfoDataTabel>({
        page: 1,
        from: 0,
        to: 0,
        total: 0,
        perPage: 25,
        search: null,
    })

    const {
        data,
        setData,
        errors,
        post,
        patch,
        delete: destroy,
        reset,
        processing,
    } = useForm()

    useEffect(() => {
        getData()
    }, [infoDataTabel.page, infoDataTabel.search, infoDataTabel.perPage])

    const getData = async () => {
        setLoading(true)
        try {
            const response = await axios.post(
                route('master.penyampaian-keterangan.data'),
                {
                    page: infoDataTabel.page,
                    search: infoDataTabel.search,
                    perPage: infoDataTabel.perPage,
                }
            )
            setDataTable(response.data.data)
            setLinksPagination(response.data.links)
            setInfoDataTabel((prev) => ({
                ...prev,
                page: response.data.current_page,
                from: response.data.from,
                to: response.data.to,
                total: response.data.total,
                perPage: response.data.per_page,
            }))
        } catch (error: any) {
            alertApp(error.message, 'error')
        } finally {
            setLoading(false)
        }
    }

    const handleForm = (e: React.FormEvent) => {
        e.preventDefault()
        const action = isEdit ? patch : post
        const routeName = isEdit
            ? (route('master.penyampaian-keterangan.update', data) as string)
            : (route('master.penyampaian-keterangan.store') as string)

        action(routeName, {
            preserveScroll: true,
            onSuccess: (e) => {
                setForm(false)
                reset()
                alertApp(e)
                getData()
            },
            onError: (e) => {
                const firstErrorKey = Object.keys(e)[0]
                if (firstErrorKey) {
                    formRefs.current[firstErrorKey]?.focus()
                }
            },
        })
    }
    const handleHapus = (e: React.FormEvent) => {
        e.preventDefault()
        destroy(route('master.penyampaian-keterangan.destroy', data), {
            preserveScroll: true,
            onSuccess: (e) => {
                setHapus(false)
                alertApp(e)
                getData()
            },
            onError: (e) => {
                alertApp(e.message, 'error')
            },
        })
    }
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
            />
            <Delete
                open={hapus}
                setOpen={setHapus}
                processing={processing}
                handleHapusData={handleHapus}
            />
        </AppLayout>
    )
}
