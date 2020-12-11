export function firstKeyInMap(mapObj){
    return Array.from(mapObj)[0][0];
}

export function firstItemInMap(mapObj){
    return Array.from(mapObj)[0][1];
}

export function lastKeyInMap(mapObj){
    return Array.from(mapObj)[mapObj.size-1][0];
}

export function lastItemInMap(mapObj){
    return Array.from(mapObj)[mapObj.size-1][1];
}
