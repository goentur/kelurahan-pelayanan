import { RefObject, useEffect, useState } from "react";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Calendar } from "@/components/ui/calendar";
import { Input } from "@/components/ui/input";
import { format } from "date-fns";
import { Label } from "./ui/label";
import { formLabel } from "./utils";
import InputError from "./input-error";

interface Props {
	id?: string;
	value: Date;
	onChange: (value: string, floatValue?: number) => void;
	inputRef?: RefObject<HTMLInputElement> | ((el: HTMLInputElement | null) => void);
	placeholder?: string;
	error?: string;
	tanggal: {tanggal_awal : Date, tanggal_akhir : Date};
	[key: string]: any;
}

export default function FormCalendarRange({
	id,
	value,
	onChange,
	inputRef,
	placeholder,
	error,
	autoOpen,
	tanggal,
	...propss }: Props) {
const [open, setOpen] = useState(false);
useEffect(() => {
	if (autoOpen) {
	setOpen(true);
	}
}, [autoOpen]);
return (
	<div className="grid gap-2">
		{id && <Label htmlFor={id} className="capitalize">
			{formLabel(id)}
		</Label>
		}
		<Popover open={open} onOpenChange={setOpen}>
			<PopoverTrigger asChild>
				<Input
					id={id}
					type="text"
					className="text-end mt-1 block w-full"
					value={value ? format(value, "dd-MM-yyyy") : ""}
					placeholder={placeholder}
					ref={inputRef}
					readOnly
					{...propss}
				/>
			</PopoverTrigger>
			<PopoverContent className="w-auto p-0">
			<Calendar
				mode="single"
				selected={value}
				onSelect={(date: Date | undefined) => {
					if (date) {
						onChange(format(date, "yyyy-MM-dd"));
					}
					setOpen(false);
				}}
				disabled={(date) => {
					const start = new Date(tanggal.tanggal_awal);
					const end = new Date(tanggal.tanggal_akhir);
					return date < start || date > end;
				}}
			/>
			</PopoverContent>
		</Popover>
		<InputError message={error} />
	</div>
);
}