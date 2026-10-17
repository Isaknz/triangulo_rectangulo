function calcularTriangulo() {
    const catetoAInput = document.getElementById('txtCatetoA');
    const catetoBInput = document.getElementById('txtCatetoB');
    const hipotenusaInput = document.getElementById('txtHipotenusa');
    const areaInput = document.getElementById('txtA');
    const perimetroInput = document.getElementById('txtP');

    if (!catetoAInput || !catetoBInput || !hipotenusaInput || !areaInput || !perimetroInput) {
        return;
    }

    const catetoA = parseFloat(catetoAInput.value);
    const catetoB = parseFloat(catetoBInput.value);

    if (isNaN(catetoA) || catetoA <= 0 || isNaN(catetoB) || catetoB <= 0) {
        alert('Ingrese catetos válidos mayores a cero');
        return;
    }

    const hipotenusa = Math.sqrt(catetoA * catetoA + catetoB * catetoB);
    const area = (catetoA * catetoB) / 2;
    const perimetro = catetoA + catetoB + hipotenusa;

    hipotenusaInput.value = hipotenusa.toFixed(2);
    areaInput.value = area.toFixed(2);
    perimetroInput.value = perimetro.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function () {
    const catetoAInput = document.getElementById('txtCatetoA');
    const catetoBInput = document.getElementById('txtCatetoB');

    if (catetoAInput) {
        catetoAInput.addEventListener('input', calcularTriangulo);
    }

    if (catetoBInput) {
        catetoBInput.addEventListener('input', calcularTriangulo);
    }
});
