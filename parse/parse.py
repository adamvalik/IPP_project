import sys
import re
import xml.etree.ElementTree as ET
import xml.dom.minidom


class Instruction:
    def __init__(self, order: int, opcode: str, args: list):
        self.order = order
        self.opcode = opcode
        self.args = args

    # validace argumentu instrukce
    def validate_args(self):
        if len(self.args) != len(self.expected_args):
            sys.stderr.write(f"[ERROR] Chybny pocet argumentu v instrukci {self.order}: {self.opcode}")
            sys.exit(23)

        for i in range(len(self.args)):
            match self.expected_args[i]:
                # regularni vyrazy na porovnani ocekavaneho formatu argumentu
                case "var": 
                    if not re.match(r"^(G|L|T)F@[a-zA-Z_\-$&%*!?][\w\-$&%*!?]*$", self.args[i]):
                        sys.stderr.write(f"[ERROR] Chybny format argumentu v instrukci {self.order}: {self.opcode}")
                        sys.exit(23)
                case "symb":
                    if not re.match(r"^((int@(\+|-)?((\d(_?\d)*)|(0[oO][0-7]+)|(0[xX][\da-fA-F]+)))|(string@([^\s#\\]|\\\d{3})*)|(bool@(true|false))|(nil@nil)|((G|L|T)F@[a-zA-Z_\-$&%*!?][\w\-$&%*!?]*))$", self.args[i]):
                        sys.stderr.write(f"[ERROR] Chybny format argumentu v instrukci {self.order}: {self.opcode}")
                        sys.exit(23)
                case "label":
                    if not re.match(r"^[a-zA-Z_\-$&%*!?][\w\-$&%*!?]*$", self.args[i]):
                        sys.stderr.write(f"[ERROR] Chybny format argumentu v instrukci {self.order}: {self.opcode}")
                        sys.exit(23)
                case "type":
                    if not re.match(r"^(int|bool|string)$", self.args[i]):
                        sys.stderr.write(f"[ERROR] Chybny format argumentu v instrukci {self.order}: {self.opcode}")
                        sys.exit(23)

    # vytvoreni xml reprezentace instrukce
    def to_xml(self, root: ET.Element):
        instr = ET.SubElement(root, "instruction")
        instr.set("order", str(self.order))
        instr.set("opcode", self.opcode)
        for i in range(len(self.args)):
            arg = ET.SubElement(instr, "arg" + str(i+1))        
            arg.set("type", self._set_type(self.args[i]))
            arg.text = self._set_text(self.args[i])

    # urceni typu argumentu
    def _set_type(self, arg: str):
        if re.match(r"^(int@)", arg):
            return "int"
        elif re.match(r"^(string@)", arg):
            return "string"
        elif re.match(r"^(bool@)", arg):
            return "bool"
        elif re.match(r"^(nil@)", arg):
            return "nil"
        elif re.match(r"^(int|bool|string)$", arg) and self.opcode == "READ":
            return "type"
        elif re.match(r"^((G|L|T)F@)", arg):
            return "var"
        elif re.match(r"^[a-zA-Z_\-$&%*!?][\w\-$&%*!?]*$", arg):
            return "label"
        else:
            return None

    # urceni textu argumentu
    def _set_text(self, arg: str):
        if re.match(r"^((int@)|(string@)|(bool@)|(nil@))", arg):
            return arg[arg.find("@")+1:]
        elif re.match(r"^((G|L|T)F@)", arg):
            return arg
        elif re.match(r"^[a-zA-Z_\-$&%*!?][\w\-$&%*!?]*$", arg):
            return arg
        else:
            return None



# CREATEFRAME, PUSHFRAME, POPFRAME, RETURN, BREAK
class InstructionType1(Instruction):
    def __init__(self, order: int, opcode: str, args: list):
        super().__init__(order, opcode, args)
        self.expected_args = []

# DEFVAR, POPS
class InstructionType2(Instruction):
    def __init__(self, order: int, opcode: str, args: list):
        super().__init__(order, opcode, args)
        self.expected_args = ["var"]

# EXIT, PUSHS, WRITE, DPRINT
class InstructionType3(Instruction):
    def __init__(self, order: int, opcode: str, args: list):
        super().__init__(order, opcode, args)
        self.expected_args = ["symb"]

# CALL, LABEL, JUMP
class InstructionType4(Instruction):
    def __init__(self, order: int, opcode: str, args: list):
        super().__init__(order, opcode, args)
        self.expected_args = ["label"]

# MOVE, TYPE, NOT, INT2CHAR, STRLEN
class InstructionType5(Instruction):
    def __init__(self, order: int, opcode: str, args: list):
        super().__init__(order, opcode, args)
        self.expected_args = ["var", "symb"]

# READ
class InstructionType6(Instruction):
    def __init__(self, order: int, opcode: str, args: list):
        super().__init__(order, opcode, args)
        self.expected_args = ["var", "type"]

# ADD, SUB, MUL, IDIV, LT, GT, EQ, AND, OR, STRI2INT, CONCAT, GETCHAR, SETCHAR
class InstructionType7(Instruction):
    def __init__(self, order: int, opcode: str, args: list):
        super().__init__(order, opcode, args)
        self.expected_args = ["var", "symb", "symb"]

# JUMPIFEQ, JUMPIFNEQ
class InstructionType8(Instruction):
    def __init__(self, order: int, opcode: str, args: list):
        super().__init__(order, opcode, args)
        self.expected_args = ["label", "symb", "symb"]



class InstructionFactory:
    @staticmethod
    def create_instruction(order: int, data: list):
        data[0] = data[0].upper()
        match data[0]:
            case "CREATEFRAME" | "PUSHFRAME" | "POPFRAME" | "RETURN" | "BREAK":
                return InstructionType1(order, data[0], data[1:])
            case "DEFVAR" | "POPS":
                return InstructionType2(order, data[0], data[1:])
            case "EXIT" | "PUSHS" | "WRITE" | "DPRINT":
                return InstructionType3(order, data[0], data[1:])
            case "CALL" | "LABEL" | "JUMP":
                return InstructionType4(order, data[0], data[1:])
            case "MOVE" | "TYPE" | "NOT" | "INT2CHAR" | "STRLEN":
                return InstructionType5(order, data[0], data[1:])
            case "READ":
                return InstructionType6(order, data[0], data[1:])
            case "ADD" | "SUB" | "MUL" | "IDIV" | "LT" | "GT" | "EQ" | "AND" | "OR" | "STRI2INT" | "CONCAT" | "GETCHAR" | "SETCHAR":
                return InstructionType7(order, data[0], data[1:])
            case "JUMPIFEQ" | "JUMPIFNEQ":
                return InstructionType8(order, data[0], data[1:])
            case _:
                sys.stderr.write(f"[ERROR] Neznamy nebo chybny operacni kod: {data[0]}")
                sys.exit(22)



HELP = """
Uziti:
    python3.10 parse.py [--help|-h]

"""


class Parser:
    def __init__(self, argv: list):
        self.args = argv
        self.argv_validate()
        self.root = ET.Element("program")

    def argv_validate(self):
        # kontrola vstupnich argumentu
        if len(self.args) == 2:
            if self.args[1] == "--help" or self.args[1] == "-h":
                print(HELP)
                sys.exit(0)
            else:
                sys.stderr.write("[ERROR] Neplatny argument\n")
                sys.stderr.write(HELP)
                sys.exit(10)
        elif len(self.args) > 2:
            sys.stderr.write("[ERROR] Neplatny pocet argumentu\n")
            sys.stderr.write(HELP)
            sys.exit(10)
        else:
            pass

    def parse(self):
        header = False
        order = 1

        data = sys.stdin.read().splitlines()
        if len(data) == 0:
            sys.stderr.write("[ERROR] Chybejici vstupni data\n")
            sys.exit(21)
        
        for line in data:
            # odstraneni komentaru
            line = re.sub(r"#.*", "", line).strip()
            # odstraneni prazdnych radku
            if line != "":
                # kontrola pritomnosti povinne hlavicky
                if header == False and line == ".IPPcode24":
                    self.root.set("language", "IPPcode24")
                    header = True
                elif header == False and line != ".IPPcode24":
                    sys.stderr.write("[ERROR] Chybejici hlavicka\n")
                    sys.exit(21)
                elif header == True and line == ".IPPcode24":
                    sys.stderr.write("[ERROR] Prilis moc hlavicek\n")
                    sys.exit(23)
                else:
                    # zpracovani instrukce
                    instr = InstructionFactory.create_instruction(order, line.split())
                    # validace argumentu instrukce
                    instr.validate_args()
                    # vytvoreni xml reprezentace instrukce
                    instr.to_xml(self.root)
                    order += 1

    def print_xml(self):
        xml_string = ET.tostring(self.root, encoding="unicode")
        print(xml.dom.minidom.parseString(xml_string).toprettyxml(encoding='UTF-8').decode())
        
    
        
##########################################################################################################


if __name__ == "__main__":
    parser = Parser(sys.argv)
    parser.parse()
    parser.print_xml()
