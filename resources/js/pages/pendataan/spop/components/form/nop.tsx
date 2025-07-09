import InputError from "@/components/input-error";
import { Input } from "@/components/ui/input";
type props = {
     data: any
     setData: React.Dispatch<React.SetStateAction<any>>
     formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
     errors: any
     selanjutnya?: string
     isEdit?: boolean
}
export default function Nop({data, setData, formRefs, errors, selanjutnya, isEdit} : props) {
     const inputFields = [
          { name: 'kd_propinsi', val: 2, next: 'kd_dati2' },
          { name: 'kd_dati2', val: 2, next: 'kd_kecamatan' },
          { name: 'kd_kecamatan', val: 3, next: 'kd_kelurahan' },
          { name: 'kd_kelurahan', val: 3, next: 'kd_blok' },
          { name: 'kd_blok', val: 3, next: 'no_urut' },
          { name: 'no_urut', val: 4, next: 'kd_jns_op' },
          { name: 'kd_jns_op', val: 1, next: selanjutnya??'' },
     ];
     return (
     <div className="grid gap-2 grid-cols-3 md:grid-cols-7">
          {inputFields.map(({ name, val, next }) => (
               <div key={name}>
                    <Input
                         className="text-center"
                         required
                         autoFocus={name === 'kd_blok'}
                         maxLength={val}
                         minLength={val}
                         readOnly={isEdit}
                         value={data[name] ?? ""}
                         onChange={(e) => {
                              const value = e.target.value;
                              setData((prevData: any) => ({ ...prevData, [name]: value }));
                              if (value.length === val && formRefs.current?.[next]) {
                                   formRefs.current[next].select();
                              }
                         }}
                         ref={(el) => {
                              if (formRefs.current) {
                                   formRefs.current[name] = el;
                              }
                         }}
                    />
                    <InputError message={errors.name} />
               </div>
          ))}

     </div>
    )
}
