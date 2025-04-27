let currentSortColumn = null;
let currentSortOrder = 'asc';

function sortTable(columnIndex) {
    const table = document.getElementById("logTable");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.querySelectorAll("tr"));

    if (currentSortColumn === columnIndex) {
        currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortOrder = 'asc';
    }
    currentSortColumn = columnIndex;

    const sortedRows = rows.sort((a, b) => {
        const A = a.children[columnIndex].textContent.trim();
        const B = b.children[columnIndex].textContent.trim();
        if (currentSortOrder === 'asc') {
            return A.localeCompare(B, undefined, {numeric: true});
        } else {
            return B.localeCompare(A, undefined, {numeric: true});
        }
    });

    tbody.innerHTML = "";
    sortedRows.forEach(row => tbody.appendChild(row));
}