import { Table, TableBody, TableCaption, TableCell, TableFooter, TableHead, TableHeader, TableRow,} from "@/components/ui/table"
type props = {
    data: any
}
export default function Tagihan({data} : props) {
     return (
          <Table>
               <TableHeader>
                    <TableRow className="border">
                         <TableHead className="w-1 border text-center">NO</TableHead>
                         <TableHead className="w-1 border text-center">TAHUN</TableHead>
                         <TableHead className="border text-center">TAGIHAN</TableHead>
                         <TableHead className="w-1 border text-center">KETERANGAN</TableHead>
                    </TableRow>
               </TableHeader>
               <TableBody>
                    {data.riwayat && data.riwayat.length > 0 ? (
                    data.riwayat.map((item: any, index: number) => (
                         <TableRow key={index}>
                              <TableCell className="border text-center">{index + 1}</TableCell>
                              <TableCell className="border text-center">{item.tahun}</TableCell>
                              <TableCell className="border text-right">{item.tagihan}</TableCell>
                              <TableCell className="border text-center">
                                   <span
                                        className={`px-2 py-1 rounded font-medium text-xs
                                        ${
                                             item.keterangan?.toLowerCase().includes('sudah')
                                             ? ' bg-green-500 text-white'
                                             : ' bg-red-500 text-white'
                                        }`}
                                   >
                                        {item.keterangan}
                                   </span>
                              </TableCell>
                         </TableRow>
                    ))
                    ) : (
                    <TableRow>
                         <TableCell
                              colSpan={4}
                              className="border-b border-gray-300 px-3 py-3 text-sm text-gray-500 text-center italic bg-light"
                         >
                              Tidak ada riwayat pembayaran
                         </TableCell>
                    </TableRow>
                    )}
               </TableBody>
          </Table>
     )
}
