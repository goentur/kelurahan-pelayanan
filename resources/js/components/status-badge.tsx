import React from 'react';

type Props = {
  value: string;
  color?: string;
};

const colorMap: Record<string, string> = {
    blue: 'bg-blue-500 text-white',
    red: 'bg-red-500 text-white',
    green: 'bg-green-500 text-white',
    yellow: 'bg-yellow-500 text-white',
    indigo: 'bg-indigo-500 text-white',
    purple: 'bg-purple-500 text-white',
    pink: 'bg-pink-500 text-white',
    gray: 'bg-gray-500 text-white',
    orange: 'bg-orange-500 text-white',
    teal: 'bg-teal-500 text-white',
    cyan: 'bg-cyan-500 text-white',
    lime: 'bg-lime-500 text-white',
    amber: 'bg-amber-500 text-white',
    emerald: 'bg-emerald-500 text-white',
    sky: 'bg-sky-500 text-white',
    rose: 'bg-rose-500 text-white',
    slate: 'bg-slate-500 text-white',
    zinc: 'bg-zinc-500 text-white',
    neutral: 'bg-neutral-500 text-white',
    stone: 'bg-stone-500 text-white',
};

const StatusBadge: React.FC<Props> = ({ value, color = 'purple' }) => {
	const badgeClass = colorMap[color] || colorMap['purple'];
	return (
		<span className={`px-1 py-0.5 rounded ${badgeClass}`}>{value}</span>
	);
};

export default StatusBadge;
