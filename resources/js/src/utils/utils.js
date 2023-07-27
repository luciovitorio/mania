import Swal from "sweetalert2";

// Função para exibir as mensagens
export function showMessage(
  msg,
  iconColor,
  iconType = "success",
  backgroundColor = "success"
) {
  const toast = Swal.mixin({
    toast: true,
    position: "top",
    showConfirmButton: false,
    iconColor: iconColor,
    timer: 3000,
    imageWidth: "1rem",
    customClass: {
      container: "toast",
      popup: `color-${backgroundColor}`,
    },
  });
  toast.fire({
    icon: iconType,
    title: msg,
    padding: "10px 20px",
  });
}

// Função para limpar os caracteres especiais dos campos
export function removeSpecialCharacters(value) {
  if (value) {
    return value.replace(/[.\-() ]/g, "");
  }
}

// Função para formatar CEP
export function formatCEP(cep) {
  if (cep) {
    return cep.slice(0, 5) + "-" + cep.slice(5);
  }
}

export function formatCPF(cpf) {
  if (cpf) {
    const digits = cpf.replace(/\D/g, "");

    if (digits.length !== 11) {
      return cpf;
    }

    const formattedCPF = `${digits.substr(0, 3)}.${digits.substr(
      3,
      3
    )}.${digits.substr(6, 3)}-${digits.substr(9, 2)}`;
    return formattedCPF;
  }
}

export function formatPhone(phone) {
  if (phone) {
    const digits = phone.replace(/\D/g, "");

    if (digits.length !== 10) {
      return phone;
    }

    const areaCode = digits.substr(0, 2);
    const firstPart = digits.substr(2, 4);
    const secondPart = digits.substr(6, 4);
    return `(${areaCode}) ${firstPart}-${secondPart}`;
  }
}

export function formatCellphone(cellphone) {
  if (cellphone) {
    const digits = cellphone.replace(/\D/g, "");

    if (digits.length !== 11) {
      return cellphone;
    }

    const ddd = digits.substr(0, 2);
    const parte1 = digits.substr(2, 5);
    const parte2 = digits.substr(7, 4);
    return `(${ddd}) ${parte1}-${parte2}`;
  }
}
