import Combobox from "@/components/combobox";
import LoadingData from "@/components/data-table/loading-data";
import NoData from "@/components/data-table/no-data";
import PerPageSelect from "@/components/data-table/per-page-select";
import FormCalendar from "@/components/form-calendar";
import { alertApp } from "@/components/utils";
import { InfoDataTabel } from "@/types";
import axios from "axios";
import { Loader2, Trash2 } from 'lucide-react';
import { useEffect, useState } from "react";
import Delete from "./delete";

type DataTableProps = {
    gate: {
        create: boolean;
        update: boolean;
        delete: boolean;
    };
    dataTable: any[];
    infoDataTabel: InfoDataTabel;
    setInfoDataTabel: React.Dispatch<React.SetStateAction<any>>
    loading: boolean;
    dataPenyampaianKeterangan: any[];
};

const filterTipe = [
    {
        label : 'SEMUA',
        class : 'bg-cyan-500 hover:bg-cyan-600',
    },
    {
        label : 'BELUM',
        class : 'bg-orange-500 hover:bg-orange-600',
    },
    {
        label : 'TERSAMPAIKAN',
        class : 'bg-green-500 hover:bg-green-600',
    },
    {
        label : 'TIDAK',
        class : 'bg-red-500 hover:bg-red-600',
    },
]
export default function DataTable({ gate, dataTable, infoDataTabel, setInfoDataTabel, loading, dataPenyampaianKeterangan }: DataTableProps) {
    const [loadingState, setLoadingState] = useState<{ [key: string]: boolean }>({});
    const [selectedItemTabel, setSelectedItemTabel] = useState<{ [key: string]: boolean }>({});
    const [editedData, setEditedData] = useState<{ [key: string]: { ya?: any; tidak?: string } }>({});
    const [deleteData, setDeleteData] = useState<{ [key: string]: boolean }>({});
    const [hapus, setHapus] = useState(false)
    const [dataYangDihapus, setDataYangDihapus] = useState<string>()
    const [loadingDeletePerItem, setLoadingDeletePerItem] = useState<{ [key: string]: boolean }>({});
    const [loadingDelete, setLoadingDelete] = useState<boolean>(false)
    const [tipe, setTipe] = useState<{ [key: string]: { type: boolean; message: string } | null }>({});
    const [messages, setMessages] = useState<{ [key: string]: { type: boolean; message: string } | null }>({});

    const handleSelectItem = (index: string, isYes: boolean) => {
        setSelectedItemTabel((prev) => ({ ...prev, [index]: isYes }));
    };

    const handleChange = async (id: string, type: "ya" | "tidak", value: any,nama_wp : string, alamat_objek : string,nominal: string) => {
        setLoadingState((prev) => ({ ...prev, [id]: true }));
        try {
            const response = await axios.post(route('transaksi.penyampaian.store'), {
                id: id,
                type: type,
                value: value,
                nama_wp: nama_wp,
                alamat_objek: alamat_objek,
                nominal: nominal,
            });
            setEditedData((prev) => ({
                ...prev,
                [id]: {
                    ya: type === "ya" ? value : null,
                    tidak: type === "tidak" ? value : null,
                },
            }));
            setTipe((prev) => ({
                ...prev,
                [id]: { type: response.data?.tipe.status, message: response.data?.tipe.value},
            }));
            setMessages((prev) => ({
                ...prev,
                [id]: { type: response.data?.status, message: response.data?.message},
            }));
            setDeleteData((prev) => ({
                ...prev,
                [id]: false,
            }));
        } catch (error:any) {
            setMessages((prev) => ({
                ...prev,
                [id]: { type: false, message: "GAGAL"},
            }));
        } finally {
            setLoadingState((prev) => ({ ...prev, [id]: false }));
        }
    };
    
    const handleDeleteItem = (id: string) => { 
        setDataYangDihapus(id);
        setHapus(true);
    }

    const deleteItem = async () => {
        if (!dataYangDihapus) return;
        const id = dataYangDihapus;
        setLoadingDeletePerItem((prev) => ({ ...prev, [id]: true }));
        setLoadingDelete(true)
        try {
            const response = await axios.post(route('transaksi.penyampaian.delete'), { id });

            if (response.data.status) {
                setDeleteData((prev) => ({ ...prev, [id]: true }));

                setEditedData((prev) => {
                    const newData = { ...prev };
                    delete newData[id];
                    return newData;
                });

                setTipe((prev) => {
                    const newTipe = { ...prev };
                    delete newTipe[id];
                    return newTipe;
                });

                setMessages((prev) => {
                    const newMessages = { ...prev };
                    delete newMessages[id];
                    return newMessages;
                });

                setSelectedItemTabel((prev) => {
                    const newSelection = { ...prev };
                    delete newSelection[id];
                    return newSelection;
                });

                alertApp(response.data.message);
            }
        } catch (error: any) {
            alertApp(error.message, "error");
        } finally {
            setLoadingDelete(false)
            setLoadingDeletePerItem((prev) => ({ ...prev, [id]: false }));
            setHapus(false);
            setDataYangDihapus(undefined);
        }
    };

    useEffect(() => {
        dataTable.forEach((value: any) => {
            if(value.penyampaian){
                setTipe((prev) => ({
                    ...prev,
                    [value.id]: { type: value.penyampaian.tipe.status, message: value.penyampaian.tipe.label},
                }));
                if (value.penyampaian.status.status) {
                    setSelectedItemTabel((prev) => ({ ...prev, [value.id]: value.penyampaian.tipe.status }));
                    setEditedData((prev) => ({
                        ...prev,
                        [value.id]: {
                            ya: value.penyampaian.tipe.status ? new Date(value.penyampaian.tipe.value) : null,
                            tidak: value.penyampaian.tipe.status ? null : value.penyampaian.tipe.value,
                        },
                    }));
                }
                setMessages((prev) => ({
                    ...prev,
                    [value.id]: { type: value.penyampaian.status.status, message: value.penyampaian.status.value},
                }));
                setDeleteData((prev) => ({
                    ...prev,
                    [value.id]: false,
                }));
            }
        });
    }, [dataTable]);
    return (
        <div>
            <ul className="mb-2 list-disc list-inside bg-blue-200 p-2 text-black rounded">
                <li>Pilih data yang ingin diperbarui.</li>
                <li>
                    Tentukan status penyampaian dengan memilih <button className="p-0.5 text-[12px] bg-green-500 text-white m-0 rounded hover:bg-green-600">YA</button> atau <button className="p-0.5 text-[12px] bg-red-500 text-white m-0 rounded hover:bg-red-600">TIDAK</button>:
                    <ul className="ml-4 list-disc list-inside">
                        <li>Jika memilih <button className="p-0.5 text-[12px] bg-green-500 text-white m-0 rounded hover:bg-green-600">YA</button>, pilih tanggal penyampaian pada kolom keterangan.</li>
                        <li>Jika memilih <button className="p-0.5 text-[12px] bg-red-500 text-white m-0 rounded hover:bg-red-600">TIDAK</button>, pilih alasan yang sesuai pada kolom keterangan.</li>
                    </ul>
                </li>
            </ul>
            <div className="mb-1 flex justify-end items-center flex-wrap gap-4">
                <div className="flex flex-wrap gap-1">
                    {filterTipe.map((item: any, index: number) => (
                    <button
                        type="button"
                        onClick={() =>
                            setInfoDataTabel((prev: any) => ({
                                ...prev,
                                page: 1,
                                tipe: item.label,
                            }))
                        }
                        disabled={loading || infoDataTabel.tipe == item.label}
                        key={index}
                        className={`${item.class} p-1 text-xs text-white rounded cursor-pointer disabled:cursor-not-allowed`}
                    >
                        {item.label}
                    </button>
                    ))}
                </div>
                <div className="flex flex-col gap-2">
                    <PerPageSelect
                        onChange={(value) =>
                            setInfoDataTabel((prev: any) => ({ ...prev, page: 1, perPage: value }))
                        }
                    />
                </div>
            </div>
            <table className="w-full text-left border-collapse border">
                <thead className="text-center text-sm">
                <tr className="uppercase leading-normal">
                    <th colSpan={3} className="px-2 py-1 border w-1">NOP</th>
                    <th className="px-2 py-1 border w-1/6">Nama wajib pajak</th>
                    <th className="px-2 py-1 border w-1/5">Alamat objek</th>
                    <th className="px-2 py-1 border w-1">Pajak</th>
                    <th className="px-2 py-1 border w-1">Tipe</th>
                    <th colSpan={2} className="px-2 py-1 border text-center">TERSAMPAIKAN?</th>
                    <th className="px-2 py-1 border">Keterangan</th>
                    <th className="px-2 py-1 border w-1">Status</th>
                    <th className="px-2 py-1 border w-1">Aksi</th>
                </tr>
                </thead>
                <tbody className="font-light text-xs">
                {loading && <LoadingData colSpan={12}/>}
                {dataTable.length > 0 ? (dataTable.map((value: any, index: number) => (
                    <tr key={index} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                        <td className="px-1 py-1 border w-1">{value.blok}</td>
                        <td className="px-1 py-1 border w-1">{value.no}</td>
                        <td className="px-1 py-1 border w-1">{value.jenis}</td>
                        <td className="px-2 py-1 border">{value.nama_wp}</td>
                        <td className="px-2 py-1 border">{value.alamat_objek}</td>
                        <td className="px-2 py-1 border text-end">{value.pajak}</td>
                        <td className="px-2 py-1 border text-center">
                            {tipe[value.id] && tipe[value.id]?.type!=null && !deleteData[value.id] && (
                                <span className={`px-1 rounded text-white ${tipe[value.id]?.type ? "bg-green-500" : "bg-red-500"}`}>
                                    {tipe[value.id]?.message}
                                </span>
                            )}
                        </td>
                        <td className="border w-1 text-center">
                            { (gate.create || gate.update) && value.penyampaian && value.penyampaian.status.status && !deleteData[value.id] && (
                                <button
                                    type="button"
                                    onClick={() => handleSelectItem(value.id, true)}
                                    className="p-0.5 text-[12px] bg-green-500 text-white m-0 rounded hover:bg-green-600 cursor-pointer disabled:cursor-not-allowed"
                                    disabled={loadingState[value.id] || loadingDeletePerItem[value.id]}
                                >
                                    {loadingState[value.id] ? <Loader2 className="animate-spin p-1" size={20} /> : 'YA'}
                                </button>
                            )}
                        </td>
                        <td className="border w-1 text-center">
                            { (gate.create || gate.update) && value.penyampaian && value.penyampaian.status.status && !deleteData[value.id] && ( 
                                <button
                                    type="button"
                                    onClick={() => handleSelectItem(value.id, false)}
                                    className="p-0.5 text-[12px] bg-red-500 text-white m-0 rounded hover:bg-red-600 cursor-pointer disabled:cursor-not-allowed"
                                    disabled={loadingState[value.id] || loadingDeletePerItem[value.id]}
                                >
                                    {loadingState[value.id] ? <Loader2 className="animate-spin p-1" size={20} /> : 'TIDAK'}
                                </button>
                            )}
                        </td>
                        <td className="px-1 py-1 border">
                            {selectedItemTabel[value.id] == true ? (
                                <FormCalendar
                                    value={editedData[value.id]?.ya || ""}
                                    onChange={(v) => handleChange(value.id, "ya", v, value.nama_wp, value.alamat_objek, value.pajak)}
                                    autoOpen={true}
                                    disabled={!gate.create || !gate.update || loadingDeletePerItem[value.id]}
                                    placeholder="Pilih tanggal"
                                />
                            ) : selectedItemTabel[value.id] == false ? (
                                <Combobox
                                    label=""
                                    selectedValue={editedData[value.id]?.tidak || ""}
                                    options={dataPenyampaianKeterangan}
                                    onSelect={(v) => handleChange(value.id, "tidak", v, value.nama_wp, value.alamat_objek, value.pajak)}
                                    autoOpen={true}
                                    disabled={!gate.create || !gate.update || loadingDeletePerItem[value.id]}
                                />
                            ) : (
                                !deleteData[value.id] && value.penyampaian?.tipe.value
                            )}
                        </td>
                        <td className="px-2 py-1 border text-center">
                            {messages[value.id] && (
                                <span className={`px-1 rounded text-white ${messages[value.id]?.type ? "bg-green-500" : "bg-red-500"}`}>
                                    {messages[value.id]?.message}
                                </span>
                            )}
                        </td>
                        <td className="px-2 py-1 border text-center">
                            {gate.delete && messages[value.id] && messages[value.id]?.type && (
                                <button
                                    type="button"
                                    onClick={() => handleDeleteItem(value.id)}
                                    className="p-0.5 bg-red-500 text-white m-0 rounded hover:bg-red-600 cursor-pointer"
                                    disabled={loadingDeletePerItem[value.id]}
                                >
                                    {loadingDeletePerItem[value.id] ? <Loader2 className="animate-spin p-1" size={20} /> : <Trash2 size={20} />}
                                </button>
                            )}
                        </td>
                    </tr>
                    ))
                ) : (!loading ?<NoData colSpan={12}/>: null)}
                </tbody>
            </table>
            <Delete
                open={hapus}
                setOpen={setHapus}
                processing={loadingDelete}
                handleHapusData={deleteItem}
            />
        </div>
    );
}
