/**
 * Valida o e-mail seguindo um padrão universal contendo '@' e domínio estruturado.
 * 
 * @param {string} emailParaValidar - O endereço de e-mail a ser validado.
 * @returns {boolean} Retorna verdadeiro se o formato for válido.
 */
export function validarFormatoDeEmail(emailParaValidar) {
  if (typeof emailParaValidar !== 'string') return false;
  const expressaoRegularDeEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  return expressaoRegularDeEmail.test(emailParaValidar);
}

/**
 * Valida matematicamente os dígitos verificadores de um número de CPF.
 * 
 * @param {string} numeroDeCpf - O CPF a ser validado (com ou sem máscara).
 * @returns {boolean} Retorna verdadeiro se o CPF for matematicamente válido.
 */
export function validarAlgoritmoDeCadastroDePessoasFisicas(numeroDeCpf) {
  if (typeof numeroDeCpf !== 'string') return false;
  
  // Remove todos os caracteres não numéricos
  const numeroDeCpfApenasDigitos = numeroDeCpf.replace(/\D/g, '');
  
  // Um CPF precisa ter exatamente 11 dígitos
  if (numeroDeCpfApenasDigitos.length !== 11) return false;
  
  // Elimina CPFs conhecidos por terem todos os dígitos iguais
  if (/^(\d)\1{10}$/.test(numeroDeCpfApenasDigitos)) return false;
  
  // Validação do primeiro dígito verificador
  let somaDosValoresDoPrimeiroDigito = 0;
  for (let indice = 0; indice < 9; indice++) {
    somaDosValoresDoPrimeiroDigito += parseInt(numeroDeCpfApenasDigitos.charAt(indice), 10) * (10 - indice);
  }
  let restoDaDivisaoDoPrimeiroDigito = (somaDosValoresDoPrimeiroDigito * 10) % 11;
  if (restoDaDivisaoDoPrimeiroDigito === 10 || restoDaDivisaoDoPrimeiroDigito === 11) {
    restoDaDivisaoDoPrimeiroDigito = 0;
  }
  if (restoDaDivisaoDoPrimeiroDigito !== parseInt(numeroDeCpfApenasDigitos.charAt(9), 10)) return false;
  
  // Validação do segundo dígito verificador
  let somaDosValoresDoSegundoDigito = 0;
  for (let indice = 0; indice < 10; indice++) {
    somaDosValoresDoSegundoDigito += parseInt(numeroDeCpfApenasDigitos.charAt(indice), 10) * (11 - indice);
  }
  let restoDaDivisaoDoSegundoDigito = (somaDosValoresDoSegundoDigito * 10) % 11;
  if (restoDaDivisaoDoSegundoDigito === 10 || restoDaDivisaoDoSegundoDigito === 11) {
    restoDaDivisaoDoSegundoDigito = 0;
  }
  if (restoDaDivisaoDoSegundoDigito !== parseInt(numeroDeCpfApenasDigitos.charAt(10), 10)) return false;
  
  return true;
}

/**
 * Aplica máscara visual ao número de CPF (000.000.000-00).
 * 
 * @param {string} numeroDeCpfBruto - O CPF bruto a ser formatado.
 * @returns {string} O CPF formatado.
 */
export function aplicarMascaraDeCadastroDePessoasFisicas(numeroDeCpfBruto) {
  if (typeof numeroDeCpfBruto !== 'string') return '';
  const numeroDeCpfApenasDigitos = numeroDeCpfBruto.replace(/\D/g, '').slice(0, 11);
  
  if (numeroDeCpfApenasDigitos.length <= 3) {
    return numeroDeCpfApenasDigitos;
  }
  if (numeroDeCpfApenasDigitos.length <= 6) {
    return `${numeroDeCpfApenasDigitos.slice(0, 3)}.${numeroDeCpfApenasDigitos.slice(3)}`;
  }
  if (numeroDeCpfApenasDigitos.length <= 9) {
    return `${numeroDeCpfApenasDigitos.slice(0, 3)}.${numeroDeCpfApenasDigitos.slice(3, 6)}.${numeroDeCpfApenasDigitos.slice(6)}`;
  }
  return `${numeroDeCpfApenasDigitos.slice(0, 3)}.${numeroDeCpfApenasDigitos.slice(3, 6)}.${numeroDeCpfApenasDigitos.slice(6, 9)}-${numeroDeCpfApenasDigitos.slice(9, 11)}`;
}

/**
 * Aplica máscara visual ao número de telefone (ex: (88) 99999-9999 ou (88) 8888-8888).
 * 
 * @param {string} numeroDeTelefoneBruto - O telefone bruto a ser formatado.
 * @returns {string} O telefone formatado.
 */
export function aplicarMascaraDeTelefone(numeroDeTelefoneBruto) {
  if (typeof numeroDeTelefoneBruto !== 'string') return '';
  const numeroDeTelefoneApenasDigitos = numeroDeTelefoneBruto.replace(/\D/g, '').slice(0, 11);
  
  if (numeroDeTelefoneApenasDigitos.length <= 2) {
    return numeroDeTelefoneApenasDigitos;
  }
  if (numeroDeTelefoneApenasDigitos.length <= 6) {
    return `(${numeroDeTelefoneApenasDigitos.slice(0, 2)}) ${numeroDeTelefoneApenasDigitos.slice(2)}`;
  }
  if (numeroDeTelefoneApenasDigitos.length <= 10) {
    return `(${numeroDeTelefoneApenasDigitos.slice(0, 2)}) ${numeroDeTelefoneApenasDigitos.slice(2, 6)}-${numeroDeTelefoneApenasDigitos.slice(6)}`;
  }
  return `(${numeroDeTelefoneApenasDigitos.slice(0, 2)}) ${numeroDeTelefoneApenasDigitos.slice(2, 7)}-${numeroDeTelefoneApenasDigitos.slice(7)}`;
}

/**
 * Aplica máscara visual à validade de cartão de crédito (MM/AA).
 * 
 * @param {string} validadeBruta - A validade bruta a ser formatada.
 * @returns {string} A validade formatada.
 */
export function aplicarMascaraDeValidadeDeCartao(validadeBruta) {
  if (typeof validadeBruta !== 'string') return '';
  const validadeApenasDigitos = validadeBruta.replace(/\D/g, '').slice(0, 4);
  if (validadeApenasDigitos.length <= 2) {
    return validadeApenasDigitos;
  }
  return `${validadeApenasDigitos.slice(0, 2)}/${validadeApenasDigitos.slice(2)}`;
}

/**
 * Valida a data de validade de um cartão de crédito.
 * 
 * @param {string} validadeString - A validade no formato MM/AA ou MMAA.
 * @returns {boolean} Retorna verdadeiro se o mês estiver entre 1 e 12 e o ano for preenchido.
 */
export function validarDataDeValidadeDeCartao(validadeString) {
  if (typeof validadeString !== 'string') return false;
  const validadeLimpa = validadeString.replace(/\D/g, '');
  if (validadeLimpa.length !== 4) return false;
  
  const mesString = validadeLimpa.slice(0, 2);
  const mesNumerico = parseInt(mesString, 10);
  
  if (mesNumerico < 1 || mesNumerico > 12) return false;
  return true;
}

/**
 * Filtra uma string para permitir apenas letras e espaços.
 * 
 * @param {string} textoAFiltrar - O texto de entrada.
 * @returns {string} O texto filtrado contendo apenas letras e espaços.
 */
export function filtrarApenasLetrasEEspacos(textoAFiltrar) {
  if (typeof textoAFiltrar !== 'string') return '';
  return textoAFiltrar.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
}

/**
 * Filtra uma string para permitir apenas caracteres alfanuméricos e espaços.
 * 
 * @param {string} textoAFiltrar - O texto de entrada.
 * @returns {string} O texto filtrado.
 */
export function filtrarApenasAlfanumericosComEspaco(textoAFiltrar) {
  if (typeof textoAFiltrar !== 'string') return '';
  return textoAFiltrar.replace(/[^a-zA-Z0-9\s]/g, '');
}

/**
 * Filtra uma string para permitir apenas dígitos numéricos.
 * 
 * @param {string} textoAFiltrar - O texto de entrada.
 * @returns {string} O texto contendo apenas dígitos numéricos.
 */
export function filtrarApenasDigitosNumericos(textoAFiltrar) {
  if (typeof textoAFiltrar !== 'string') return '';
  return textoAFiltrar.replace(/\D/g, '');
}

/**
 * Aplica máscara visual ao número de CNPJ (XX.XXX.XXX/XXXX-XX).
 * 
 * @param {string} cnpjBruto - O CNPJ bruto a ser formatado.
 * @returns {string} O CNPJ formatado.
 */
export function aplicarMascaraDeCadastroNacionalDaPessoaJuridica(cnpjBruto) {
  if (typeof cnpjBruto !== 'string') return '';
  const cnpjApenasDigitos = cnpjBruto.replace(/\D/g, '').slice(0, 14);
  if (cnpjApenasDigitos.length <= 2) return cnpjApenasDigitos;
  if (cnpjApenasDigitos.length <= 5) return `${cnpjApenasDigitos.slice(0, 2)}.${cnpjApenasDigitos.slice(2)}`;
  if (cnpjApenasDigitos.length <= 8) return `${cnpjApenasDigitos.slice(0, 2)}.${cnpjApenasDigitos.slice(2, 5)}.${cnpjApenasDigitos.slice(5)}`;
  if (cnpjApenasDigitos.length <= 12) return `${cnpjApenasDigitos.slice(0, 2)}.${cnpjApenasDigitos.slice(2, 5)}.${cnpjApenasDigitos.slice(5, 8)}/${cnpjApenasDigitos.slice(8)}`;
  return `${cnpjApenasDigitos.slice(0, 2)}.${cnpjApenasDigitos.slice(2, 5)}.${cnpjApenasDigitos.slice(5, 8)}/${cnpjApenasDigitos.slice(8, 12)}-${cnpjApenasDigitos.slice(12, 14)}`;
}
