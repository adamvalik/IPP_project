import sys
import re
import xml.etree.ElementTree as ET
import xml.dom.minidom



class Instruction:
    instruction_list = []

    opcode_dict = {
        "MOVE": ["var", "symb"],
        "CREATEFRAME": [],
        "PUSHFRAME": [],
        "POPFRAME": [],
        "DEFVAR": ["var"],
        "CALL": ["label"],
        "RETURN": [],
        "PUSHS": ["symb"],
        "POPS": ["var"],
        "ADD": ["var", "symb", "symb"],
        "SUB": ["var", "symb", "symb"],
        "MUL": ["var", "symb", "symb"],
        "IDIV": ["var", "symb", "symb"],
        "LT": ["var", "symb", "symb"],
        "GT": ["var", "symb", "symb"],
        "EQ": ["var", "symb", "symb"],
        "AND": ["var", "symb", "symb"],
        "OR": ["var", "symb", "symb"],
        "NOT": ["var", "symb"],
        "INT2CHAR": ["var", "symb"],
        "STRI2INT": ["var", "symb", "symb"],
        "READ": ["var", "type"],
        "WRITE": ["symb"],
        "CONCAT": ["var", "symb", "symb"],
        "STRLEN": ["var", "symb"],
        "GETCHAR": ["var", "symb", "symb"],
        "SETCHAR": ["var", "symb", "symb"],
        "TYPE": ["var", "symb"],
        "LABEL": ["label"],
        "JUMP": ["label"],
        "JUMPIFEQ": ["label", "symb", "symb"],
        "JUMPIFNEQ": ["label", "symb", "symb"],
        "EXIT": ["symb"],
        "DPRINT": ["symb"],
        "BREAK": [],
    }

    # constructor
    def __init__(self, order: int, opcode: str, args: list):
        self.order = order
        self.opcode = opcode
        self.args = args
        self.instruction_list.append(self)
        self.validate_args()

    # validate arguments based on the opcode
    def validate_args(self):
        expect = Instruction.opcode_dict[self.opcode]
        if len(self.args) != len(expect):
            sys.stderr.write(f"Chybný počet argumentů v instrukci:  {self.order} {self.opcode}")
            sys.exit(23)
        for i in range(len(self.args)):
            match expect[i]:
                case "var":
                    if not re.match(r"^(G|L|T)F@[a-zA-Z_\-$&%*!?][\w\-$&%*!?]*$", self.args[i]):
                        sys.stderr.write(f"Chybný formát argumentu v instrukci:  {self.order} {self.opcode} : {self.args[i]} ")
                        sys.exit(23)
                case "symb":
                    if not re.match(r"^((int@(\+|-)?((\d(_?\d)*)|(0[oO][0-7]+)|(0[xX][\da-fA-F]+)))|(string@([^\s#\\]|\\\d{3})*)|(bool@(true|false))|(nil@nil)|((G|L|T)F@[a-zA-Z_\-$&%*!?][\w\-$&%*!?]*))$", self.args[i]):
                        sys.stderr.write(f"Chybný formát argumentu v instrukci:  {self.order} {self.opcode} : {self.args[i]} ")
                        sys.exit(23)
                case "label":
                    if not re.match(r"^[a-zA-Z_\-$&%*!?][\w\-$&%*!?]*$", self.args[i]):
                        sys.stderr.write(f"Chybný formát argumentu v instrukci:  {self.order} {self.opcode} : {self.args[i]} ")
                        sys.exit(23)
                case "type":
                    if not re.match(r"^(int|bool|string)$", self.args[i]):
                        sys.stderr.write(f"Chybný formát argumentu v instrukci:  {self.order} {self.opcode} : {self.args[i]} ")
                        sys.exit(23)

    # convert instruction to xml
    def to_xml(self, root: ET.Element):
        instr = ET.SubElement(root, "instruction")
        instr.set("order", str(self.order))
        instr.set("opcode", self.opcode)
        for i in range(len(self.args)):
            arg = ET.SubElement(instr, "arg" + str(i+1))        
            arg.set("type", self._set_type(self.args[i]))
            arg.text = self._set_text(self.args[i])

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

    def _set_text(self, arg: str):
        if re.match(r"^((int@)|(string@)|(bool@)|(nil@))", arg):
            return arg[arg.find("@")+1:]
        elif re.match(r"^((G|L|T)F@)", arg):
            return arg
        elif re.match(r"^[a-zA-Z_\-$&%*!?][\w\-$&%*!?]*$", arg):
            return arg
        else:
            return None


def argv_validate(args: list):
    # parsovani argumentu
    if len(args) == 2:
        if args[1] == "--help" or args[1] == "-h":
            print("UsAgE:HeLp Me PlEaSe")
            sys.exit(0)
        else:
            sys.stderr.write("Neplatný argument\n")
            sys.exit(10)
    elif len(args) > 2:
        sys.stderr.write("Neplatný počet argumentů")
        sys.exit(10)
    else:
        pass


def main():
    argv_validate(sys.argv)

    header = False
    cnt = 1
    root = ET.Element("program")

    data = sys.stdin.read().splitlines()
    if len(data) == 0:
        sys.stderr.write("Chybějící vstupní data")
        sys.exit(21)
    
    for line in data:
        # odstraneni komentaru
        line = re.sub(r"#.*", "", line).strip()
        #odstraneni prazdnych radku
        if line != "":
            if header == False and line == ".IPPcode24":
                root.set("language", "IPPcode24")
                header = True
            elif header == False and line != ".IPPcode24":
                sys.stderr.write("Chybějící hlavička")
                sys.exit(21)
            elif header == True and line == ".IPPcode24":
                sys.stderr.write("too many headers")
                sys.exit(23)
            else:
                data = line.split()
                instr = Instruction(cnt, data[0].upper(), data[1:]) 
                cnt += 1


    for instr in Instruction.instruction_list:
        instr.to_xml(root)

    tree = ET.ElementTree(root)

    xml_string = ET.tostring(root, encoding="unicode")

    print(xml.dom.minidom.parseString(xml_string).toprettyxml(encoding='UTF-8').decode())


if __name__ == "__main__":
    main()
    sys.exit(0)